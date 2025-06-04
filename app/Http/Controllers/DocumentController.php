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
    public function index()
    {
        $customers = Customer::orderBy('name')->get();

        return view('pages.document.index', compact('customers'));
    }

    public function getDocumentData(Request $request)
    {
        $customerId = $request->input('customer_id');
        $orderDate = $request->input('order_date');

        if (!$customerId || !$orderDate) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $orders = Order::with(['orderItems.goods', 'orderItems.specification'])
            ->where('customer_id', $customerId)
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate)
            ->get();

        $customer = Customer::find($customerId);
        

        $data = [];
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $name = $item->goods->name;
                $unit = $item->goods->unit;
                $qty = $item->quantity;
                $specification = $item->specification ? $item->specification->name : '-';

                // Buat key unik berdasarkan nama + spesifikasi supaya beda spesifikasi tetap terpisah
                $key = $name . '||' . $specification;

                if (isset($data[$key])) {
                    $data[$key]['qty'] += $qty;
                } else {
                    $data[$key] = [
                        'name' => $name,
                        'unit' => $unit,
                        'qty' => $qty,
                        'specification' => $specification,
                    ];
                }
            }
        }

        $data = array_values($data);

        return response()->json([
            'customer' => $customer->name,
            'address' => $customer->company_address,
            'order_code' => $orders->first()->order_code,
            'data' => $data,
        ]);

    }
}
