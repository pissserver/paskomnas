<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;

class PackingController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return view('pages.packing.index', compact('customers'));
    }

    public function packingSummary(Request $request)
    {
        $orderDate = $request->input('order_date', Carbon::today()->toDateString());

        $orders = Order::with(['orderItems.goods', 'orderItems.specification', 'customer'])
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate)
            ->get();

        $customers = [];
        $dataTable = [];

        foreach ($orders as $order) {
            $customerId = $order->customer_id;
            $customerName = $order->customer->name;
            $customers[$customerId] = $customerName;

            foreach ($order->orderItems as $item) {
                $goodsName = $item->goods->name;
                $unit = $item->goods->unit;
                $spec = $item->specification ? $item->specification->name : null;

                $key = $spec ? "$goodsName ($spec)" : $goodsName;

                if (!isset($dataTable[$key])) {
                    $dataTable[$key] = [
                        'unit' => $unit,
                        'quantities' => [],
                        'total' => 0,
                    ];
                }

                if (!isset($dataTable[$key]['quantities'][$customerId])) {
                    $dataTable[$key]['quantities'][$customerId] = [];
                }

                $dataTable[$key]['quantities'][$customerId][] = [
                    'qty' => $item->quantity,
                    'order_code' => $order->order_code,
                ];

                $dataTable[$key]['total'] += $item->quantity;
            }
        }

        ksort($dataTable);

        if ($request->ajax()) {
            return response()->json([
                'customers' => $customers,
                'dataTable' => $dataTable,
            ]);
        }

        return view('pages.purchase.index', [
            'selectedDate' => $orderDate,
        ]);
    }
}
