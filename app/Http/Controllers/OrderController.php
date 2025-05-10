<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        $totalCustomers = $customers->count();
        return view('pages.order.index', compact('customers', 'totalCustomers'));
    }

    public function create($id)
    {
        $customer = Customer::findOrFail($id);
        $goods = Goods::orderBy('name', 'asc')->get();

        return view('pages.order.create', compact('customer', 'goods'));
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
            'quantity' => 'required|array',
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
                $quantity = $request->quantity[$index];
        
                // Ambil harga barang
                $goods = Goods::findOrFail($goodsId);

                // Hitung total harga
                $total = $goods->price * $quantity;
        
                $orderItems[] = new OrderItem([
                    'goods_id' => $goodsId,
                    'quantity' => $quantity,
                    'price' => $goods->price,
                    'total' => $total,
                ]);
            }
        
            // Simpan semua order items
            $order->orderItems()->saveMany($orderItems);
        
            DB::commit(); // Commit transaksi
        
            // Kembali ke halaman dengan pesan sukses
            return redirect()->route('order.create', ['id' => $order->customer_id])->with('success', 'Order dengan kode ' .$order->order_code. ' berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada kesalahan
        
            // Log pesan error untuk debugging
            \Log::error('Error saving order: ' . $e->getMessage());
        
            return back()->withErrors(['error' => $e->getMessage()]);
        }   
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
