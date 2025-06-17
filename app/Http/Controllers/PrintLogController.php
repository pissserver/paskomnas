<?php

namespace App\Http\Controllers;

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

            $log = LogPrint::create([
                'print_date' => Carbon::now(),
                'purchase' => 'done', // ← Tandai bahwa halaman purchase sudah diprint
            ]);

            foreach ($request->order_ids as $orderId) {
                LogPrintDetail::create([
                    'log_print_id' => $log->id,
                    'order_id' => $orderId,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Print log saved.',
                'log_id' => $log->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving log print.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
