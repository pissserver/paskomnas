<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('pages.user-management.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = User::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('role', 'like', "%$keyword%")
                ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE ?", ["%$keyword%"]);
            });
        }

        $users = $query->orderBy('role', 'asc')->orderBy('name', 'asc')->paginate(10);

        $html = view('components.tables.user-management-table', compact('users'))->render();
        $pagination = view('components.pagination.user-pagination', compact('users'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        return view('pages.user-management.create');
    }

    public function store(Request $request, User $user)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')],
            'password' => ['required', 'min:8', 'max:50', 'confirmed'],
            'phone' => ['max:50'],
            'location' => ['max:255'],
            'role' => ['max:50'],
            'about_me' => ['max:255'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToCreate = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
            'phone' => $attributes['phone'],
            'location' => $attributes['location'],
            'role' => $attributes['role'],
            'about_me' => $attributes['about_me'],
        ];

        $lastId = User::max('id');
        $lastId += 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'user_' . $lastId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/profile', $filename);
            $dataToCreate['photo'] = 'storage/profile/' . $filename;
        }
        
        $user->create($dataToCreate);

        return redirect('/user-management')->with('success','Pengguna baru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user-management.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'max:50', 'confirmed'],
            'phone' => ['max:50'],
            'location' => ['max:70'],
            'role' => ['max:50'],
            'about_me' => ['max:150'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'] ? bcrypt($attributes['password']) : $user->password,
            'phone' => $attributes['phone'],
            'location' => $attributes['location'],
            'role' => $attributes['role'],
            'about_me' => $attributes['about_me'],
        ];

        if ($request->hasFile('photo')) {   
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $file = $request->file('photo');
            $filename = 'user_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/profile', $filename);
            $dataToUpdate['photo'] = 'storage/profile/' . $filename;
        } elseif ($request->input('reset_photo') == '1') {
            $dataToUpdate['photo'] = null;
        }
        
        
        $user->update($dataToUpdate);

        return redirect()->route('user-management.edit', ['id' => $user->id])
            ->with('success', 'Informasi pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }
        $user->delete();

        return redirect('/kelola-pengguna')->with('success', 'Pengguna berhasil dihapus');
    }   
}
