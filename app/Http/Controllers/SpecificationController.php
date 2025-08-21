<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specification;

class SpecificationController extends Controller
{
    public function index()
    {
        return view('pages.specification.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Specification::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }

        $specifications = $query->orderBy('name', 'asc')->paginate(10);

        $html = view('components.tables.specification-table', compact('specifications'))->render();
        $pagination = view('components.pagination.specification-pagination', compact('specifications'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        return view('pages.specification.create');
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
        ];

        Specification::create($dataToCreate);

        return redirect()->route('specification.index')->with('success', $request->name . ' Berhasil ditambahkan ke data spesifikasi komoditi');
    }

    public function edit(String $id)
    {
        $specification = Specification::findOrFail($id);
        return view('pages.specification.edit', compact('specification'));
    }

    public function update(Request $request, string $id)
    {
        $specification = Specification::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],  
        ];

        $specification->update($dataToUpdate);

        return redirect()->route('specification.index')->with('success', $request->name .' berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $specification = Specification::findOrFail($id);
        $specification->delete();

        return redirect()->route('specification.index')->with('success', 'Barang komoditi berhasil dihapus.');
    }
}
