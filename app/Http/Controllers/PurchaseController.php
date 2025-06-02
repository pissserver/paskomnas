<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;

class PurchaseController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $goods = Goods::all();

        return view('pages.purchase.index', [
            'customers' => $customers,
            'goods' => $goods,
        ]);
    }
}
