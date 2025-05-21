<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goods;
use Illuminate\Validation\Rule;

class GoodsController extends Controller
{
    public function index()
    {
        return view('pages.goods.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Goods::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('unit', 'like', "%$keyword%");
            });
        }

        $goods = $query->orderBy('id', 'asc')->paginate(10);

        $html = view('components.tables.goods-table', compact('goods'))->render();
        $pagination = view('components.pagination.goods-pagination', compact('goods'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        return view('pages.goods.create');
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'unit' => ['required', 'max:50'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
            'unit' => $attributes['unit'],
        ];

        Goods::create($dataToCreate);

        return redirect()->route('goods.index')->with('success', $request->name .' berhasil ditambahkan ke Barang Komoditi.');
    }

    public function edit(string $id)
    {
        $goods = Goods::findOrFail($id);
        return view('pages.goods.edit', compact('goods'));
    }

    public function update(Request $request, string $id)
    {
        $goods = Goods::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'unit' => ['required', 'max:50'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
            'unit' => $attributes['unit'],
        ];

        $goods->update($dataToUpdate);

        return redirect()->route('goods.index')->with('success', $request->name .' berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $goods = Goods::findOrFail($id);
        $goods->delete();

        return redirect()->route('goods.index')->with('success', 'Barang komoditi berhasil dihapus.');
    }
}
