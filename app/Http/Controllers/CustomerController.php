<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerGroup;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Goods;
use App\Models\Periode;
use App\Models\Price;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    // Group Customer
    public function groupIndex()
    {
        $customerGroups = CustomerGroup::with(['customers'])
            ->orderBy('name', 'asc')
            ->get();
        $totalCustomerGroups = $customerGroups->count();

        return view('pages.customer-group.index', compact('customerGroups', 'totalCustomerGroups'));
    }

    public function groupCreate()
    {
        return view('pages.customer-group.create');
    }

    public function groupStore(Request $request)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
        ];

        $lastId = CustomerGroup::max('id');
        $lastId += 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'customer-group_' . $lastId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer-group', $filename);
            $dataToCreate['photo'] = 'storage/customer-group/' . $filename;
        }

        CustomerGroup::create($dataToCreate);

        return redirect()->route('customer.group.index')->with('success', 'Customer group baru berhasil ditambahkan.');
    }

    public function groupEdit(string $id)
    {
        $customerGroup = CustomerGroup::findOrFail($id);
        return view('pages.customer-group.edit', compact('customerGroup'));
    }

    public function groupUpdate(Request $request, string $id)
    {
        $customerGroup = CustomerGroup::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
        ];

        if ($request->hasFile('photo')) {
            if ($customerGroup->photo && file_exists(public_path($customerGroup->photo))) {
                unlink(public_path($customerGroup->photo));
            }
            $file = $request->file('photo');
            $filename = 'customer-group_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer-group', $filename);
            $dataToUpdate['photo'] = 'storage/customer-group/' . $filename;
        }

        $customerGroup->update($dataToUpdate);

        return redirect()->route('customer.group.index', $id)->with('success', 'Customer group berhasil diperbarui.');
    }

    public function groupDestroy(string $id)
    {
        $customerGroup = CustomerGroup::findOrFail($id);

        if ($customerGroup->photo && file_exists(public_path($customerGroup->photo))) {
            unlink(public_path($customerGroup->photo));
        }

        $customerGroup->delete();

        Customer::where('customer_group_id', $id)->update(['customer_group_id' => null]);

        return redirect()->route('customer.group.index')->with('success', 'Customer group berhasil dihapus.');
    }

    // Customer
    public function index()
    {
        return view('pages.customer.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Customer::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('company_address', 'like', "%$keyword%")
                    ->orWhere('npwp', 'like', "%$keyword%");
            });
        }

        $customers = $query->orderBy('name', 'asc')->paginate(10);

        $html = view('components.tables.customer-table', compact('customers'))->render();
        $pagination = view('components.pagination.customer-pagination', compact('customers'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        $customerGroups = CustomerGroup::orderBy('name', 'asc')->get();

        return view('pages.customer.create', compact('customerGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_group_id' => ['nullable', 'exists:customer_groups,id'],
            'name' => ['required', 'max:50'],
            'company_address' => ['required', 'max:255'],
            'no_telp' => ['required', 'max:20'],
            'email' => ['required', 'email', 'max:100', Rule::unique('customers')],
            'npwp' => ['required', 'max:50', Rule::unique('customers')],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $data = $request->only((new \App\Models\Customer)->getFillable());

        $checkboxFields = [
            'copy_ktp',
            'copy_npwp',
            'copy_tdp',
            'copy_akta',
            'copy_domisili',
            'copy_siup',
            'company_profile',
            'other_docs',
        ];

        foreach ($checkboxFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }

        $lastId = Customer::max('id');
        $lastId += 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'customer_' . $lastId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer', $filename);
            $data['photo'] = 'storage/customer/' . $filename;
        }

        $customer = Customer::create($data);

        return redirect()->route('customer.index')->with('success', 'Customer baru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $customerGroups = CustomerGroup::orderBy('name', 'asc')->get();
        $customer = Customer::withCount('orders')->findOrFail($id);
        $orderCount = $customer->orders_count;

        if ($orderCount >= 500) {
            $level = 'Platinum';
            $badgeClass = 'bg-gradient-dark';
        } elseif ($orderCount >= 100) {
            $level = 'Gold';
            $badgeClass = 'bg-gradient-warning';
        } elseif ($orderCount >= 20) {
            $level = 'Silver';
            $badgeClass = 'bg-gradient-secondary';
        } else {
            $level = 'Basic';
            $badgeClass = 'bg-gradient-light text-dark';
        }

        $goods = Goods::orderBy('name', 'asc')->get();

        $ownPeriode = Periode::where('customer_id', $customer->id)->first();

        $groupPeriode = null;

        if (!$ownPeriode && $customer->customer_group_id) {
            $groupCustomerIds = Customer::where('customer_group_id', $customer->customer_group_id)
                ->where('id', '!=', $customer->id)
                ->pluck('id');

            $groupPeriode = Periode::whereIn('customer_id', $groupCustomerIds)
                ->withCount('prices')
                ->orderByDesc('prices_count')
                ->first();
        }

        // Pilih sumber harga dari ownPeriode kalau ada, kalau tidak ambil dari groupPeriode
        $pricePeriode = $ownPeriode ? $ownPeriode : $groupPeriode;

        $pricesMap = [];

        if ($pricePeriode) {
            $prices = Price::where('periode_id', $pricePeriode->id)->get();
            foreach ($prices as $price) {
                $pricesMap[$price->goods_id] = $price->price;
            }
        }

        $goods->each(function ($item) use ($pricesMap) {
            $item->price = isset($pricesMap[$item->id]) ? $pricesMap[$item->id] : null;
        });

        return view('pages.customer.edit', compact('customerGroups', 'customer', 'level', 'badgeClass', 'goods', 'ownPeriode', 'groupPeriode'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'customer_group_id' => ['nullable', 'exists:customer_groups,id'],
            'name' => ['required', 'max:50'],
            'company_address' => ['required', 'max:255'],
            'no_telp' => ['required', 'max:20'],
            'email' => ['required', 'email', 'max:100', Rule::unique('customers')->ignore($customer->id)],
            'npwp' => ['required', 'max:50', Rule::unique('customers')->ignore($customer->id)],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $data = $request->only((new \App\Models\Customer)->getFillable());

        $checkboxFields = [
            'copy_ktp',
            'copy_npwp',
            'copy_tdp',
            'copy_akta',
            'copy_domisili',
            'copy_siup',
            'company_profile',
            'other_docs',
        ];

        foreach ($checkboxFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'customer_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer', $filename);
            $data['photo'] = 'storage/customer/' . $filename;
        }

        $customer->update($data);

        return redirect()->route('customer.edit', $id)->with('success', 'Customer berhasil diperbarui.');
    }

    public function updatePrice(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        // create or update periode
        $periodeAttributes = request()->validate([
            'periode_name' => ['required', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $dataPeriode = [
            'periode_name' => $periodeAttributes['periode_name'],
            'start_date' => $periodeAttributes['start_date'],
            'end_date' => $periodeAttributes['end_date'],
            'customer_id' => $customer->id,
        ];

        $periode = Periode::updateOrCreate(
            ['customer_id' => $customer->id],
            $dataPeriode
        );

        // create or update price
        $priceAttributes = $request->validate([
            'goods_id' => ['required', 'array'],
            'goods_id.*' => ['exists:goods,id'],

            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($priceAttributes['goods_id'] as $index => $goodsId) {
            $dataPrice = [
                'periode_id' => $periode->id,
                'goods_id' => $goodsId,
                'price' => $priceAttributes['price'][$index],
            ];

            Price::updateOrCreate(
                [
                    'periode_id' => $periode->id,
                    'goods_id' => $goodsId,
                ],
                $dataPrice
            );
        }

        return redirect()->route('customer.edit', $customer->id)->with('success', 'Harga berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->photo && file_exists(public_path($customer->photo))) {
            unlink(public_path($customer->photo));
        }

        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function customerOrders(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $keyword = $request->input('keyword');
        $query = Order::where('customer_id', $customer->id);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', "%$keyword%")
                    ->orWhereRaw("DATE_FORMAT(order_date, '%W, %d %M %Y') LIKE ?", ["%$keyword%"])
                    ->orWhere('status', 'like', "%$keyword%");
            });
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(10);

        $html = view('components.tables.customer-order-table', compact('orders'))->render();
        $pagination = view('components.pagination.customer-order-pagination', compact('orders'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function customerInvoice(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $query = Order::with('orderItems.goods')
            ->where('customer_id', $customer->id)
            ->where('status', 'confirmed');

        $orders = $query->orderBy('order_date', 'desc')->get();

        foreach ($orders as $order) {
            $order->total_with_ppn = $order->orderItems->sum(function ($item) {
                $subtotal = $item->total;
                if ($item->goods && $item->goods->ppn) {
                    $subtotal += ($subtotal * 11 / 100);
                }
                return $subtotal;
            });
        }

        $totalAll = $orders->sum('total_with_ppn');

        $html = view('components.tables.customer-invoice-table', compact('orders', 'totalAll'))->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
