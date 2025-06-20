<?php

namespace App\Http\Controllers;

use App\Models\Specification;
use App\Models\Order;
use App\Models\LogPrint;
use App\Models\LogPrintDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PrintLogController extends Controller
{
    public function dataPurchaseLogPrint(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = LogPrint::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->WhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE ?", ["%$keyword%"]);
            });
        }

        $logs = $query->with(['details.order.customer'])
            ->where('purchase', 'done')
            ->orderBy('print_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $html = view('components.tables.log-print-purchase-group-table', compact('logs'))->render();
        $pagination = view('components.pagination.log-print-purchase-group-pagination', compact('logs'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function purchaseLogPrint(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        try {
            DB::beginTransaction();

            // Ambil order_id yang belum pernah masuk ke log print
            $existingOrderIds = LogPrintDetail::whereIn('order_id', $request->order_ids)
                ->pluck('order_id')
                ->toArray();

            $newOrderIds = array_diff($request->order_ids, $existingOrderIds);

            // Kalau semua sudah pernah diprint, tidak usah insert apa-apa
            if (empty($newOrderIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua order sudah pernah dicetak.',
                ]);
            }

            // Ambil customer group dari salah satu order baru
            $firstOrder = Order::with('customer')->find($newOrderIds[0]);
            $customerGroupId = null;

            if ($firstOrder && $firstOrder->customer) {
                $customerGroupId = $firstOrder->customer->customer_group_id;
            }

            // Simpan log
            $log = LogPrint::create([
                'print_date' => now(),
                'purchase' => 'done',
                'customer_group_id' => $customerGroupId,
            ]);

            foreach ($newOrderIds as $orderId) {
                LogPrintDetail::create([
                    'log_print_id' => $log->id,
                    'order_id' => $orderId,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Log print berhasil disimpan.',
                'log_id' => $log->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan log print.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function printLogView($id)
    {
        $log = LogPrint::with([
            'details.order.orderItems.goods',
            'details.order.customer',
            'customerGroup'
        ])->findOrFail($id);

        $isGroup = $log->customer_group_id !== null;

        $groupName = null;
        $customerName = null;

        if ($isGroup) {
            $groupName = $log->customerGroup ? $log->customerGroup->name : 'Unknown Group';
            $grouped = [];
            $customers = [];

            foreach ($log->details as $detail) {
                $order = $detail->order;
                $custId = $order->customer->id;
                $custName = $order->customer->name;
                $customers[$custId] = $custName;

                foreach ($order->orderItems as $item) {
                    $goodsName = $item->goods->name;
                    $unit = $item->goods->unit;
                    $spec = $item->specification ? $item->specification->name : null;
                    $key = $spec ? "$goodsName ($spec)" : $goodsName;

                    if (!isset($grouped[$key])) {
                        $grouped[$key] = [
                            'unit' => $unit,
                            'total' => 0,
                            'quantities' => []
                        ];
                    }

                    $grouped[$key]['total'] += $item->quantity;
                    if (!isset($grouped[$key]['quantities'][$custId])) {
                        $grouped[$key]['quantities'][$custId] = 0;
                    }
                    $grouped[$key]['quantities'][$custId] += $item->quantity;

                }
            }

            ksort($grouped);
            sort($customers);
            return response()->json([
                'success' => true,
                'group_name' => $groupName,
                'grouped' => $grouped,
                'customers' => $customers,
            ]);
        } else {
            // Individual customer
            $items = [];
            $customerNames = [];

            foreach ($log->details as $detail) {
                $customer = $detail->order->customer;
                if ($customer) {
                    $customerNames[] = $customer->name;
                }

                foreach ($detail->order->orderItems as $item) {
                    $goodsName = $item->goods->name;
                    $spec = $item->specification ? $item->specification->name : null;
                    $key = $spec ? "$goodsName ($spec)" : $goodsName;

                    if (!isset($items[$key])) {
                        $items[$key] = [
                            'quantity' => 0,
                            'unit' => $item->goods->unit,
                        ];
                    }
                    $items[$key]['quantity'] += $item->quantity;
                }
            }

            $customerNames = array_unique($customerNames);
            ksort($items);
            sort($customerNames);

            return response()->json([
                'success' => true,
                'items' => $items,
                'customer_name' => implode(', ', $customerNames),
            ]);
        }
    }

}
