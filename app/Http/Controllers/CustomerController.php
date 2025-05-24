<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Goods;
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

        return view('pages.customer.edit', compact('customer', 'level', 'badgeClass', 'goods'));
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
