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
        $selectedOrderId = $request->input('order_id');

        if (!$customerId || !$orderDate) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Ambil semua PO untuk dropdown
        $orders = Order::where('customer_id', $customerId)
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['error' => 'No orders found'], 404);
        }

        // Ambil 1 PO secara eksplisit berdasarkan ID (pastikan dengan relasi!)
        $selectedOrder = Order::with(['orderItems.goods', 'orderItems.specification'])
            ->where('customer_id', $customerId)
            ->where('status', 'confirmed')
            ->whereDate('order_date', $orderDate)
            ->when($selectedOrderId, function ($query) use ($selectedOrderId) {
                return $query->where('id', $selectedOrderId);
            })
            ->first();

        if (!$selectedOrder) {
            return response()->json(['error' => 'Selected order not found'], 404);
        }

        // Ambil customer
        $customer = Customer::find($customerId);

        // Susun data item dari PO terpilih
        $data = [];
        $hasPpn = false;
        foreach ($selectedOrder->orderItems as $item) {
            $name = $item->goods->name;
            $unit = $item->goods->unit;
            $ppn = $item->goods->ppn;
            $qty = $item->quantity;
            $specification = $item->specification ? $item->specification->name : '-';
            $key = $name . '||' . $specification;

            // Cek jika PPN lebih dari 0
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
}
