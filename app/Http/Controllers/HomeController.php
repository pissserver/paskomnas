<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Goods;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Order;
use App\Models\PurchasePrice;

class HomeController extends Controller
{
    public function home()
    {
        return redirect('dashboard');
    }

    public function index()
    {
        $startOfYear = Carbon::now()->startOfYear(); // 1 Januari tahun ini
        $endOfNow = Carbon::now(); // waktu saat ini
        
        // * Count data
        $users = User::count();
        $customers = Customer::count();
        $goods = Goods::count();
        $orders = Order::whereBetween('order_date', [$startOfYear, $endOfNow])->count();

        $mostCustomerOrdered = Order::with('customer')
            ->selectRaw('customer_id, COUNT(*) as order_count')
            ->groupBy('customer_id')
            ->orderByDesc('order_count')
            ->first();
        $mostGroupOrdered = CustomerGroup::with('customers.orders')
            ->get()
            ->map(function ($group) {
                $orderCount = $group->customers->sum(function ($customer) {
                    return $customer->orders->count();
                });

                return (object)[
                    'name' => $group->name,
                    'group' => $group,
                    'order_count' => $orderCount,
                ];
            })
            ->sortByDesc('order_count')
            ->first();

        // * Chart data
        $ordersData = Order::selectRaw('MONTH(order_date) as month, COUNT(*) as total')
            ->whereYear('order_date', now()->year)
            ->groupByRaw('MONTH(order_date)')
            ->orderByRaw('MONTH(order_date)')
            ->get();

        // Siapkan data lengkap untuk semua bulan (1–12)
        $chartLabels = [];
        $chartData = [];

        foreach (range(1, 12) as $month) {
            $monthName = Carbon::create()->month($month)->translatedFormat('F');
            $chartLabels[] = $monthName;

            $monthOrder = $ordersData->firstWhere('month', $month);
            $chartData[] = $monthOrder ? $monthOrder->total : 0;
        }


        // * Statistic Omzet & Pengeluaran

        $year = now()->year;

        $expenses = PurchasePrice::with('logPrint')
            ->whereHas('logPrint', function ($q) use ($year) {
                $q->whereYear('print_date', $year);
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->logPrint->print_date->format('n'); // bulan 1-12
            })
            ->map(function ($items) {
                return $items->sum('total_price');
            })
            ->toArray();

        // isi semua bulan (1–12)
        $dataExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            if (isset($expenses[$i])) {
                $dataExpenses[] = $expenses[$i];
            } else {
                $dataExpenses[] = 0;
            }
        }

        $labels = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $dataOmzet = [50, 40, 300, 220, 500, 250, 400, 230, 500, 300, 200, 450];

        return view('pages.dashboard', 
            compact(
                'startOfYear', 
                'endOfNow',
                'users', 
                'customers', 
                'goods', 
                'orders', 
                'mostCustomerOrdered', 
                'mostGroupOrdered',
                'chartLabels',
                'chartData',
                'labels',
                'dataOmzet', 
                'dataExpenses'
                )
        );
    }

    public function dataGroup()
    {
        $lastMonth = now()->subMonth();
        $start = $lastMonth->copy()->startOfMonth();
        $end = $lastMonth->copy()->endOfMonth();

        $groups = CustomerGroup::with(['customers.orders' => function ($query) use ($start, $end) {
            $query->whereBetween('order_date', [$start, $end]);
        }, 'customers'])->get();

        // Hitung max order untuk normalisasi percent
        $maxOrder = $groups->map(function ($group) {
            return $group->customers->sum(function ($customer) {
                return $customer->orders->count();
            });
        })->max() ?: 1;

        // Transformasi data
        $dataGroupProgression = $groups->map(function ($group) use ($maxOrder) {
            $orderCount = $group->customers->sum(function ($c) {
                return $c->orders->count();
            });

            return [
                'name' => $group->name,
                'logo' => $group->photo ? asset($group->photo) : asset('assets/img/default-profile.jpg'),
                'orders' => $orderCount,
                'ordersPercent' => round(($orderCount / $maxOrder) * 100),
                'members' => $group->customers->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'name' => $m->name,
                        'photo' => $m->photo ? asset($m->photo) : asset('assets/img/default-profile.jpg'),
                    ];
                }),
            ];
        })->sortByDesc('orders')->values();

        return response()->json($dataGroupProgression);
    }

    public function dataCustomer()
    {
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $customers = Customer::whereNull('customer_group_id')
            ->with(['orders' => function ($q) use ($lastMonth, $endOfLastMonth) {
                $q->whereBetween('order_date', [$lastMonth, $endOfLastMonth]);
            }])
            ->get();

        $maxOrder = $customers->map(function ($c) {
            return $c->orders->count();
        })->max() ?: 1;


        return response()->json(
            $customers->map(function ($c) use ($maxOrder) {
                return [
                    'name' => $c->name,
                    'logo' => $c->photo ? $c->photo : asset('assets/img/default-profile.jpg'),
                    'orders' => $c->orders->count(),
                    'ordersPercent' => round(($c->orders->count() / $maxOrder) * 100),
                ];
            })->sortByDesc('orders')->values()
        );
    }
}
