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

    public function groupCustomer(Request $request)
    {
        $orderDate = $request->input('order_date', Carbon::today()->toDateString());

        $orders = Order::with(['orderItems.goods', 'orderItems.specification', 'customer.customerGroup'])
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate)
            ->get();

        $groupedData = [];

        foreach ($orders as $order) {
            if (!$order->customer->customer_group_id || !$order->customer->customerGroup) {
                continue;
            }

            $group = $order->customer->customerGroup->name;
            $customerId = $order->customer_id;
            $customerName = $order->customer->name;

            // Inisialisasi grup
            if (!isset($groupedData[$group])) {
                $groupedData[$group] = [
                    'customers' => [],
                    'dataTable' => [],
                    'orderIds' => [],
                ];
            }

            // Tambahkan order_id ke daftar orderIds grup
            if (!in_array($order->id, $groupedData[$group]['orderIds'])) {
                $groupedData[$group]['orderIds'][] = $order->id;
            }

            // Tambah customer ke grup
            $groupedData[$group]['customers'][$customerId] = $customerName;

            // Loop item
            foreach ($order->orderItems as $item) {
                $goodsName = $item->goods->name;
                $unit = $item->goods->unit;
                $spec = $item->specification ? $item->specification->name : null;

                $key = $spec ? "$goodsName ($spec)" : $goodsName;

                // Init dataTable row
                if (!isset($groupedData[$group]['dataTable'][$key])) {
                    $groupedData[$group]['dataTable'][$key] = [
                        'unit' => $unit,
                        'quantities' => [],
                        'total' => 0,
                    ];
                }

                if (!isset($groupedData[$group]['dataTable'][$key]['quantities'][$customerId])) {
                    $groupedData[$group]['dataTable'][$key]['quantities'][$customerId] = 0;
                }

                $groupedData[$group]['dataTable'][$key]['quantities'][$customerId] += $item->quantity;
                $groupedData[$group]['dataTable'][$key]['total'] += $item->quantity;
            }
        }

        if ($request->ajax()) {
            return response()->json($groupedData);
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
            ->whereDate('order_date', $orderDate)
            ->whereHas('customer', function ($q) {
                $q->whereNull('customer_group_id'); // Hanya customer tanpa grup
            });

        if ($customerId && $customerId !== 'all') {
            $query->where('customer_id', $customerId);
        }

        $orders = $query->get();

        $items = [];
        $customerNames = [];

        foreach ($orders as $order) {
            $customerName = $order->customer->name;
            if (!in_array($customerName, $customerNames)) {
                $customerNames[] = $customerName;
            }

            foreach ($order->orderItems as $item) {
                $goodsName = $item->goods->name;
                $spec = $item->specification ? $item->specification->name : null;
                $key = $spec ? "$goodsName ($spec)" : $goodsName;

                if (!isset($items[$key])) {
                    $items[$key] = 0;
                }

                $items[$key] += $item->quantity;
            }
        }

        // Urutkan nama komoditi
        ksort($items);

        if ($request->ajax()) {
            $customerList = $orders->pluck('customer')->unique('id')->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name
                ];
            })->values();

            return response()->json([
                'items' => $items,
                'customer_names' => $customerNames,
                'customer_list' => $customerList,
            ]);
        }

        $customers = Customer::whereNull('customer_group_id')->orderBy('name')->get();

        return view('pages.purchase.index', [
            'selectedDate' => $orderDate,
            'customers' => $customers,
        ]);
    }
}
