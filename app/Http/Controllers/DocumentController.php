<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderConvert;

class DocumentController extends Controller
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

            $customer = Customer::find($customerId);

            $data = [];
            $hasPpn = false;
            foreach ($selectedOrder->orderItems as $item) {
                $name = $item->goods->name;
                $unit = $item->goods->unit_in_order !== null ? $item->goods->unit_in_order : $item->goods->unit;
                $ppn = $item->goods->ppn;
                $qty = $item->quantity;
                $specification = $item->specification ? $item->specification->name : '';
                $key = $name . '||' . $specification;

                if (!$hasPpn && floatval($ppn) > 0) {
                    $hasPpn = true;
                }

                // Cek apakah data order item sudah ada di OrderConvert
                $goodsId = $item->goods_id;
                $specificationId = $item->specification_id;

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
            }

            return response()->json([
                'customer' => $customer->name,
                'address' => $customer->company_address,
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

        return view('pages.document.index', compact('customers'));
    }

    public function storeConversion(Request $request)
    {
        $count = count($request->order_id);
        $convertedItems = [];

        for ($i = 0; $i < $count; $i++) {
            $orderId         = $request->order_id[$i];
            $goodsId         = $request->goods_id[$i];
            $specificationId = $request->specification_id[$i];
            $originalQty     = $request->original_quantity[$i];
            $originalUnit    = $request->original_unit[$i];

            $newQty  = $request->new_quantity[$i];
            $newUnit = $request->new_unit[$i];

            $finalQty  = $newQty !== null && $newQty !== '' ? $newQty : $originalQty;
            $finalUnit = $newUnit !== null && $newUnit !== '' ? $newUnit : $originalUnit;

            // Optional: hapus data sebelumnya jika ingin replace
            $converted = OrderConvert::updateOrCreate(
                [
                    'order_id'         => $orderId,
                    'goods_id'         => $goodsId,
                    'specification_id' => $specificationId ? $specificationId : null,
                ],
                [
                    'quantity' => $finalQty,
                    'unit'     => $finalUnit,
                ]
            );

            // Update total di OrderItem jika ada convert
            $orderItem = OrderItem::where('order_id', $orderId)
                ->where('goods_id', $goodsId)
                ->when($specificationId, function ($q) use ($specificationId) {
                    return $q->where('specification_id', $specificationId);
                }, function ($q) {
                    return $q->whereNull('specification_id');
                })
                ->first();  

            if ($orderItem) {
                $orderItem->total = $finalQty * $orderItem->price;
                $orderItem->save();
            }

            $convertedItems[] = [
                'order_id' => $orderId,
                'goods_id' => $goodsId,
                'specification_id' => $specificationId,
                'quantity' => $converted->quantity,
                'unit' => $converted->unit,
            ];
        }

        return response()->json([
            'message' => 'Berhasil melakukan konversi.',
            'converted' => $convertedItems,
        ]);
    }

}
