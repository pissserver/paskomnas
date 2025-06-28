<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use App\Models\Customer;
use App\Models\Periode;
use App\Models\Price;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderConvert;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $orderDate = $request->input('order_date', Carbon::today()->toDateString());
        $customerId = $request->input('customer_id');
        $orderId = $request->input('order_id');

        // Untuk dropdown customer (jika hanya order_date dikirim)
        if ($request->ajax() && $orderDate && !$customerId) {
            $customers = Customer::whereHas('orders', function ($q) use ($orderDate) {
                $q->whereDate('order_date', $orderDate)->where('status', 'confirmed');
            })->orderBy('name')->get();

            return response()->json([
                'customers' => $customers->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                    ];
                })
            ]);
        }

        // Untuk data dokumen (jika order_date + customer_id dikirim)
        if ($request->ajax() && $orderDate && $customerId) {
            $orders = Order::where('customer_id', $customerId)
                ->where('status', 'confirmed')
                ->whereDate('order_date', $orderDate)
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['error' => 'No orders found'], 404);
            }

            $selectedOrder = Order::with(['orderItems.goods', 'orderItems.specification'])
                ->where('customer_id', $customerId)
                ->where('status', 'confirmed')
                ->whereDate('order_date', $orderDate)
                ->when($orderId, function ($q) use ($orderId) {
                    return $q->where('id', $orderId);
                })
                ->first();

            if (!$selectedOrder) {
                return response()->json(['error' => 'Selected order not found'], 404);
            }

            // 🔎 Cari periode aktif milik customer
            $periode = Periode::with('prices')
                ->where('customer_id', $customerId)
                ->first();

            // 💰 Ambil daftar harga dari periode (pakai keyBy supaya mudah dicari pakai goods_id)
            $prices = $periode ? $periode->prices->keyBy('goods_id') : collect();

            $customer = Customer::find($customerId);

            $data = [];
            $grandTotal = 0;
            $totalPPN = 0;
            $hasPpn = false;
            foreach ($selectedOrder->orderItems as $item) {
                $name = $item->goods->name;
                $unit = $item->goods->unit;
                $ppn = floatval($item->goods->ppn);
                $qty = $item->quantity;
                $specification = $item->specification ? $item->specification->name : '';
                $key = $name . '||' . $specification;

                
                // Cek apakah data order item sudah ada di OrderConvert
                $goodsId = $item->goods_id;
                $specificationId = $item->specification_id;

                // Ambil harga dari periode (jika ada)
                $price = $prices->get($goodsId)->price ? $prices->get($goodsId)->price : '-';
                $totalPrice = $price * $qty;
                $grandTotal += $totalPrice;

                if ($ppn > 0) {
                    $hasPpn = true;
                    $totalPPN += $totalPrice * (11 / 100);
                }

                $converted = OrderConvert::where('order_id', $selectedOrder->id)
                    ->where('goods_id', $goodsId)
                    ->when(!is_null($specificationId), function ($q) use ($specificationId) {
                        return $q->where('specification_id', $specificationId);
                    }, function ($q) {
                        return $q->whereNull('specification_id');
                    })
                    ->first();

                $usedQty = $converted ? $converted->quantity : $qty;
                $usedUnit = $converted ? $converted->unit : $unit;
                $displayQty = $converted ? $converted->quantity : '';
                $displayUnit = $converted ? $converted->unit : '';

                if (isset($data[$key])) {
                    $data[$key]['qty'] += $usedQty;
                } else {
                    $data[$key] = [
                        'goods_id' => $item->goods_id,
                        'price_formatted' => number_format($price, 0, ',', '.'),
                        'total_price_formatted' => number_format($totalPrice, 0, ',', '.'),
                        'specification_id' => $item->specification_id,
                        'name' => $name,
                        'original_unit' => $unit,
                        'unit' => $usedUnit,
                        'display_unit' => $displayUnit,
                        'ppn' => $ppn,
                        'original_qty' => $qty,
                        'qty' => $usedQty,
                        'display_qty' => $displayQty,
                        'specification' => $specification,
                    ];
                }

                $grandTotalWithPPN = $grandTotal + $totalPPN;
            }

            return response()->json([
                'customer' => $customer->name,
                'address' => $customer->company_address,
                'npwp' => $customer->npwp,
                'grand_total' => number_format($grandTotal, 0, ',', '.'),
                'grand_total_with_ppn' => number_format($grandTotalWithPPN, 0, ',', '.'),
                'order_code' => $selectedOrder->order_code,
                'reference_customer' => $selectedOrder->reference_customer,
                'order_id' => $selectedOrder->id,
                'ppn_status' => $hasPpn ? 'Dengan PPN' : 'NON PPN',
                'data' => array_values($data),
                'po_list' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'code' => $order->order_code,
                    ];
                }),
            ]);
        }

        // Untuk tampilan awal (non-AJAX)
        $customers = collect(); // default kosong

        return view('pages.invoice.index', compact('customers'));
    }
}
