<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\LogPrintDetail;

class PurchasePriceController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return view('pages.purchase-price.index', compact('customers'));
    }
}
