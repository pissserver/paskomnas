<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Periode;
use App\Models\Price;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Function order
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->orderBy('name', 'asc')
            ->get();
        $totalCustomers = $customers->count();

        return view('pages.order.index', compact('customers', 'totalCustomers'));
    }

    public function create($customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $goods = Goods::orderBy('name', 'asc')->get();

        $periode = Periode::with(['prices.goods'])
            ->where('customer_id', $customerId)
            ->first();

        $specifications = Specification::orderBy('name', 'asc')->get();

        $allOrders = Order::where('customer_id', $customerId)->get();

        $orders = Order::with(['customer', 'orderItems.goods'])
            ->where('customer_id', $customerId)
            ->latest()
            ->first();

        return view('pages.order.create', compact(
            'customer',
            'goods',
            'specifications',
            'allOrders',
            'orders',
            'periode'
        ));
    }

    public function generateOrderCode($customerId)
    {
        // Ambil kode customer (misalnya: '01', '02', dst)
        $customerCode = str_pad($customerId, 2, '0', STR_PAD_LEFT);

        // Ambil tahun saat ini
        $year = now()->format('Y');

        // Hitung nomor urut order berdasarkan customer
        $lastOrder = DB::table('orders')
            ->where('customer_id', $customerId)
            ->whereYear('created_at', now()->year)
            ->orderBy('order_code', 'desc')
            ->first();

        if ($lastOrder) {
            // Ambil nomor urut terakhir dan increment
            $lastNumber = (int)substr($lastOrder->order_code, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada order, mulai dari 00001
            $nextNumber = '00001';
        }

        // Format order_code sesuai format yang diminta
        return 'PO' . $customerCode . '-' . $year . $nextNumber;
    }

    public function store(Request $request, $customerId)
    {
        // Validasi input
        $request->validate([
            'order_date' => 'required|date',
            'goods_id' => 'required|array',
            'specification_id' => 'nullable|array',
            'specification_id.*' => 'nullable|exists:specifications,id',
            'quantity' => 'required|array',
            'price' => 'array',
            'goods_id.*' => 'exists:goods,id', // Memastikan goods_id valid
            'quantity.*' => 'required|integer|min:1', // Validasi jumlah barang
        ]);

        DB::beginTransaction(); // Mulai transaksi

        try {
            // Simpan Order
            $order = new Order();
            $order->customer_id = $customerId;
            $order->order_date = $request->order_date;
        
            // Generate order_code
            $order->order_code = $this->generateOrderCode($customerId);
            $order->save();
        
            // Simpan OrderItems
            $orderItems = [];
            foreach ($request->goods_id as $index => $goodsId) {
                $specificationId = isset($request->specification_id[$index]) ? $request->specification_id[$index] : null;
                $quantity = $request->quantity[$index];
                $price = $request->price[$index];

                // Hitung total harga
                $total = $price * $quantity;
        
                $orderItems[] = new OrderItem([
                    'goods_id' => $goodsId,
                    'specification_id' => $specificationId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);
            }
        
            // Simpan semua order items
            $order->orderItems()->saveMany($orderItems);
        
            DB::commit(); // Commit transaksi
        
            // Kembali ke halaman dengan pesan sukses
            return redirect()->route('order.create', ['customer_id' => $order->customer_id])->with('success', 'Order dengan kode ' .$order->order_code. ' berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada kesalahan
        
            // Log pesan error untuk debugging
            \Log::error('Error saving order: ' . $e->getMessage());
        
            return back()->withErrors(['error' => $e->getMessage()]);
        }   
    }

    public function data(Request $request, string $customerId)
    {
        DB::statement("SET lc_time_names = 'id_ID'");

        $keyword = $request->input('keyword');
        $query = Order::with('customer')
            ->where('customer_id', $customerId);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%$keyword%")
                    ->orWhere('status', 'like', "%$keyword%")
                    ->orWhereRaw("DATE_FORMAT(order_date, '%W, %d %M %Y') LIKE ?", ["%$keyword%"]);
            });
        }

        $orders = $query->orderBy('order_code', 'desc')->paginate(10);

        $html = view('components.tables.order-table', compact('orders'))->render();
        $pagination = view('components.pagination.order-pagination', compact('orders'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function show(string $customerId, string $orderId)
    {
        $order = Order::with(['customer', 'orderItems.goods', 'orderItems.specification'])
            ->where('customer_id', $customerId)
            ->where('id', $orderId)
            ->firstOrFail();

        $goods = Goods::all();

        $periode = Periode::with(['prices.goods'])
            ->where('customer_id', $customerId)
            ->first();

        $specifications = Specification::orderBy('name', 'asc')->get();

        return view('pages.order.detail-order', compact('order', 'goods', 'periode', 'specifications'));
    }

    public function update(Request $request, string $customerId, string $orderId)
    {
        $order = Order::with(['customer', 'orderItems'])->where('customer_id', $customerId)->where('id', $orderId)->firstOrFail();

        $request->validate([
            'order_date' => 'required|date',
            'goods_id' => 'required|array',
            'specification_id' => 'nullable|array',
            'specification_id.*' => 'nullable|exists:specifications,id',
            'quantity' => 'required|array',
            'price' => 'required|array',
            'goods_id.*' => 'required|exists:goods,id',
            'quantity.*' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Update tanggal order
            $order->order_date = $request->order_date;
            $order->save();

            $existingItemIds = []; // Untuk menampung ID item yang tetap ada

            foreach ($request->goods_id as $index => $goodsId) {
                $quantity = $request->quantity[$index];
                $specificationId = isset($request->specification_id[$index]) ? $request->specification_id[$index] : null;
                $price = $request->price[$index];
                $orderItemId = $request->order_item_id[$index];
                $total = $price * $quantity;

                $goods = Goods::findOrFail($goodsId);

                if ($orderItemId !== null && $orderItemId !== '') {
                    // Update data jika item lama
                    $orderItem = OrderItem::where('order_id', $order->id)->where('id', $orderItemId)->first();
                    if ($orderItem) {
                        $orderItem->goods_id = $goodsId;
                        $orderItem->specification_id = $specificationId;
                        $orderItem->quantity = $quantity;
                        $orderItem->price = $price;
                        $orderItem->total = $total;
                        $orderItem->save();

                        $existingItemIds[] = $orderItemId;
                    }
                } else {
                    // Tambahkan item baru
                    $newItem = $order->orderItems()->create([
                        'goods_id' => $goodsId,
                        'specification_id' => $specificationId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                    ]);

                    $existingItemIds[] = $newItem->id;
                }
            }

            // Hapus item yang tidak ada di form
            $order->orderItems()->whereNotIn('id', $existingItemIds)->delete();

            DB::commit();

            return redirect()
                ->route('order.show', ['customer_id' => $order->customer_id, 'order_id' => $order->id])
                ->with('success', 'Order berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating order: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memperbarui order: ' . $e->getMessage()]);
        }
    }

    // Function process & cancel order
    public function orderProcessView()
    {
        $query = Order::with(['customer', 'orderItems.goods'])
            ->where('status', 'confirmed');
        $orderProcess = $query->orderBy('order_date', 'desc')->orderBy('order_code', 'desc')->paginate(10);
        
        $cancelQuery = Order::with(['customer', 'orderItems.goods'])
            ->where('status', 'cancelled');
        $orderCancel = $cancelQuery->orderBy('order_date', 'desc')->orderBy('order_code', 'desc')->paginate(10);
        
        return view('pages.order.order-process', compact('orderProcess', 'orderCancel'));
    }

    public function orderProcessData(Request $request)
    {
        DB::statement("SET lc_time_names = 'id_ID'");

        $keyword = $request->input('keyword');
        $query = Order::with(['customer', 'orderItems.goods'])
            ->where('status', 'confirmed');

        $goods = Goods::all();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%$keyword%")
                ->orWhereRaw("DATE_FORMAT(order_date, '%W, %d %M %Y') LIKE ?", ["%$keyword%"])
                ->orWhereHas('customer', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%$keyword%");
                });
            });
        }

        $orderProcesses = $query->orderBy('order_date', 'desc')->orderBy('order_code', 'desc')->paginate(10);

        $html = view('components.tables.order-process-table', compact('orderProcesses', 'goods'))->render();
        $pagination = view('components.pagination.order-process-pagination', compact('orderProcesses', 'goods'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function orderCancelData(Request $request)
    {
        DB::statement("SET lc_time_names = 'id_ID'");

        $keyword = $request->input('keyword');
        $query = Order::with(['customer', 'orderItems.goods'])
            ->where('status', 'cancelled');

        $goods = Goods::all();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%$keyword%")
                ->orWhereRaw("DATE_FORMAT(order_date, '%W, %d %M %Y') LIKE ?", ["%$keyword%"])
                ->orWhereHas('customer', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%$keyword%");
                });
            });
        }

        $orderCancelled = $query->orderBy('order_date', 'desc')->orderBy('order_code', 'desc')->paginate(10);

        $html = view('components.tables.order-cancel-table', compact('orderCancelled', 'goods'))->render();
        $pagination = view('components.pagination.order-cancel-pagination', compact('orderCancelled', 'goods'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function orderCancelAction($id)
    {
        $cancelOrder = Order::with('customer')
            ->findOrFail($id);

        $cancelOrder->status = 'cancelled';
        $cancelOrder->save();

        return redirect()->back()
            ->with('success', 'Order dengan kode order <b>' . e($cancelOrder->order_code) . '</b> dari customer <b>' . e($cancelOrder->customer->name) . '</b> berhasil dibatalkan.');
    }

    public function orderProcessAction($id)
    {
        $processOrder = Order::with('customer')
            ->findOrFail($id);

        $processOrder->status = 'confirmed';
        $processOrder->save();

        return redirect()->back()
            ->with('success', 'Order dengan kode order <b>' . e($processOrder->order_code) . '</b> dari customer <b>' . e($processOrder->customer->name) . '</b> berhasil diproses kembali.');
    }
}
