<?php

namespace App\Http\Controllers;

use App\Models\ImageArchive;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImageArchiveController extends Controller
{
    public function index()
    {
        $archives = ImageArchive::all();
        return view('pages.image-archive.index', ['archives' => $archives]);
    }

    public function store(Request $request, ImageArchive $imageArchive) 
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'description' => ['nullable', 'string'],
            'photo' => ['required', 'image', 'max:10240'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
            'description' => $attributes['description'],
        ];

        $lastId = ImageArchive::max('id');
        $lastId += 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'image_' . $lastId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/archive', $filename);
            $dataToCreate['photo'] = 'storage/archive/' . $filename;
        }
        
        $imageArchive->create($dataToCreate);

        return redirect()->route('image-archive.index')->with('success','Data berhasil ditambahkan');
    }

    public function data(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = ImageArchive::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%")
                ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE ?", ["%$keyword%"]);
            });
        }

        $archives = $query->orderBy('created_at', 'desc')->paginate(10);

        $html = view('components.tables.image-archive-table', compact('archives'))->render();
        $pagination = view('components.pagination.image-archive-pagination', compact('archives'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function edit($id)
    {
        $archive = ImageArchive::findOrFail($id);
        return view('pages.image-archive.edit', ['archive' => $archive]);
    }

    public function update(Request $request, $id)
    {
        $archive = ImageArchive::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'description' => ['nullable', 'string'],
            'photo' => ['image', 'max:10240'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
            'description' => $attributes['description'],
        ];

        if ($request->hasFile('photo')) {
            if ($archive->photo && file_exists(public_path($archive->photo))) {
                unlink(public_path($archive->photo));
            }

            $file = $request->file('photo');
            $filename = 'image_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/archive', $filename);
            $dataToUpdate['photo'] = 'storage/archive/' . $filename;
        }

        $archive->update($dataToUpdate);

        return redirect()->route('image-archive.index')->with('success','Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $archive = ImageArchive::findOrFail($id);

        if ($archive->photo && file_exists(public_path($archive->photo))) {
            unlink(public_path($archive->photo));
        }

        $archive->delete();

        return redirect()->route('image-archive.index')->with('success','Data berhasil dihapus');
    }

    public function deleteAllSelected(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date)->endOfDay();
    
        $delete = ImageArchive::whereBetween('created_at', [$start, $end])->get();
        
        foreach ($delete as $archive) {
            if ($archive->photo && file_exists(public_path($archive->photo))) {
                unlink(public_path($archive->photo));
            }
            $archive->delete();
        }


        return redirect('/request-order')->with('success','Data berhasil dihapus dari tanggal ' . Carbon::parse($request->start_date)->translatedFormat('d F Y') . ' sampai ' . Carbon::parse($request->end_date)->translatedFormat('d F Y'));
    }
}
