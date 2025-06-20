<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;

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
                $unit = $item->goods->unit;
                $ppn = $item->goods->ppn;
                $qty = $item->quantity;
                $specification = $item->specification ? $item->specification->name : '-';
                $key = $name . '||' . $specification;

                if (!$hasPpn && floatval($ppn) > 0) {
                    $hasPpn = true;
                }

                if (isset($data[$key])) {
                    $data[$key]['qty'] += $qty;
                } else {
                    $data[$key] = [
                        'name' => $name,
                        'unit' => $unit,
                        'ppn' => $ppn,
                        'qty' => $qty,
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
}
