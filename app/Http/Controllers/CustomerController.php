<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Goods;
use App\Models\Periode;
use App\Models\Price;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
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
                    ->orWhere('address', 'like', "%$keyword%")
                    ->orWhere('npwp', 'like', "%$keyword%")
                    ->orWhere('delivery_address', 'like', "%$keyword%")
                    ->orWhere('pic_receiver', 'like', "%$keyword%");
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
        return view('pages.customer.create');
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'address' => ['required', 'max:255'],
            'npwp' => ['required', 'max:50', Rule::unique('customers')],
            'delivery_address' => ['nullable', 'max:255'],
            'pic_receiver' => ['required', 'max:50'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
            'address' => $attributes['address'],
            'npwp' => $attributes['npwp'],
            'delivery_address' => $attributes['delivery_address'],
            'pic_receiver' => $attributes['pic_receiver'],
        ];

        $lastId = Customer::max('id');
        $lastId += 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'customer_' . $lastId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer', $filename);
            $dataToCreate['photo'] = 'storage/customer/' . $filename;
        }

        $customer = Customer::create($dataToCreate);

        return redirect()->route('customer.index')->with('success', 'Customer baru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
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
        $periode = Periode::where('customer_id', $customer->id)->first();
        
        if ($periode) {
            $goods->each(function ($item) use ($periode) {
                $item->price = Price::where('periode_id', $periode->id)
                    ->where('goods_id', $item->id)
                    ->value('price');
            });
        } else {
            $goods->each(function ($item) {
                $item->price = null;
            });
        }

        return view('pages.customer.edit', compact('customer', 'level', 'badgeClass', 'goods', 'periode'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'address' => ['required', 'max:255'],
            'npwp' => ['required', 'max:50', Rule::unique('customers')->ignore($customer->id)],
            'delivery_address' => ['nullable', 'max:255'],
            'pic_receiver' => ['required', 'max:50'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
            'address' => $attributes['address'],
            'npwp' => $attributes['npwp'],
            'delivery_address' => $attributes['delivery_address'],
            'pic_receiver' => $attributes['pic_receiver'],
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'customer_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/customer', $filename);
            $dataToUpdate['photo'] = 'storage/customer/' . $filename;
        }

        $customer->update($dataToUpdate);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
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
}
