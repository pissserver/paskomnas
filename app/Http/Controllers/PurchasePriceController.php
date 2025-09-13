<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PurchasePrice;
use App\Models\LogPrint;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\LogPrintDetail;

class PurchasePriceController extends Controller
{
    public function index()
    {
        return view('pages.purchase-price.index');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'log_print_id'    => ['required', 'integer', 'exists:log_prints,id'],
            'order_id'        => ['required', 'array'],
            'order_id.*'      => ['required', 'integer', 'exists:orders,id'],
            'goods_id'        => ['required', 'array'],
            'goods_id.*'      => ['required', 'integer', 'exists:goods,id'],
            'specification_id'=> ['nullable', 'array'],
            'specification_id.*'=> ['nullable', 'integer', 'exists:specifications,id'],
            'unit'          => ['required', 'array'],
            'unit.*'        => ['required', 'string', 'max:20'],
            'unit_purchase' => ['nullable', 'array'],
            'unit_purchase.*'=> ['nullable', 'string', 'max:20'],
            'quantity'        => ['nullable', 'array'],
            'quantity.*'      => ['nullable', 'numeric', 'min:0'],
            'purchase_price'  => ['required', 'array'],
            'purchase_price.*'=> ['required', 'numeric', 'min:0'],
            'total_price'     => ['required', 'array'],
            'total_price.*'   => ['required', 'numeric', 'min:0'],
        ]);

        // Looping simpan data
        foreach ($request->goods_id as $index => $goodsId) {
            PurchasePrice::updateOrCreate([
                'log_print_id'     => $request->log_print_id,
                'order_id'         => $request->order_id[$index],
                'goods_id'         => $goodsId,
                'specification_id' => $request->specification_id[$index] ? $request->specification_id[$index] : null,
            ],
            [
                'unit' => $request->unit_purchase[$index] !== null && $request->unit_purchase[$index] !== ''
                            ? $request->unit_purchase[$index]
                            : NULL,
                'quantity'         => $request->quantity[$index] ? $request->quantity[$index] : 0,
                'purchase_price'   => $request->purchase_price[$index],
                'total_price'      => $request->total_price[$index],
            ]);
        }


        return redirect()->route('purchase-price.index')->with('success', 'Harga beli berhasil disimpan.');
    }

}
