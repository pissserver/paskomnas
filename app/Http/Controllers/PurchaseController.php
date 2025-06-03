<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;

class PurchaseController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return view('pages.purchase.index', compact('customers'));
    }

    public function purchaseSummary(Request $request)
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
                    $dataTable[$key]['quantities'][$customerId] = 0;
                }

                $dataTable[$key]['quantities'][$customerId] += $item->quantity;
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

    public function purchaseCustomer(Request $request)
    {
        $orderDate = $request->input('order_date', Carbon::today()->toDateString());
        $customerId = $request->input('customer_id');

        $query = Order::with(['orderItems.goods', 'orderItems.specification', 'customer'])
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate);

        if ($customerId && $customerId !== 'all') {
            $query->where('customer_id', $customerId);
        }

        $orders = $query->get();

        $dataPerCustomer = [];

        foreach ($orders as $order) {
            $cId = $order->customer_id;
            $customerName = $order->customer->name;

            if (!isset($dataPerCustomer[$cId])) {
                $dataPerCustomer[$cId] = [
                    'customer_name' => $customerName,
                    'total_quantity' => 0,
                    'items' => [],
                ];
            }

            foreach ($order->orderItems as $item) {
                $goodsName = $item->goods->name;
                $spec = $item->specification ? $item->specification->name : null;
                $key = $spec ? "$goodsName ($spec)" : $goodsName;

                if (!isset($dataPerCustomer[$cId]['items'][$key])) {
                    $dataPerCustomer[$cId]['items'][$key] = 0;
                }
                $dataPerCustomer[$cId]['items'][$key] += $item->quantity;
                $dataPerCustomer[$cId]['total_quantity'] += $item->quantity;
            }
        }

        ksort($dataPerCustomer);

        if ($request->ajax()) {
            return response()->json([
                'data' => $dataPerCustomer,
            ]);
        }

        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('pages.purchase.index', [
            'selectedDate' => $orderDate,
            'customers' => $customers,
        ]);
    }

}
