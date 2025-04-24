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
        return view('laravel-examples.user-management');
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
        $pagination = view('components.user-pagination', compact('users'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination
        ]);
    }

    public function create()
    {
        return view('laravel-examples/user-create');
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

        return redirect('/user-management')->with('success','User created successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('laravel-examples/user-edit', ['user' => $user]);
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
            ->with('success', 'Profile updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }
        $user->delete();

        return redirect('/user-management')->with('success', 'User deleted successfully');
    }   

    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');

    //     $users = User::where('name', 'LIKE', "%$keyword%")
    //         ->orWhere('email', 'LIKE', "%$keyword%")
    //         ->orWhere('role', 'LIKE', "%$keyword%")
    //         ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE ?", ["%$keyword%"])
    //         ->orderBy('role', 'asc')
    //         ->orderBy('name', 'asc')
    //         ->paginate(10);

    //         $html = '';
    //         foreach ($users as $index => $user) {
    //             $html .= '<tr>';
    //             $html .= '<td class="ps-4"><p class="text-xs font-weight-bold mb-0">' . ($index + 1) . '</p></td>';
    //             $html .= '<td><img src="' . asset(!empty($user->photo) ? $user->photo : 'assets/img/default-profile.jpg') . '" class="avatar avatar-sm me-3"></td>';
    //             $html .= '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' . $user->name . '</p></td>';
    //             $html .= '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' . $user->email . '</p></td>';
    //             $html .= '<td class="text-center"><span class="mb-0 btn btn-sm ' . ($user->role == "master" ? "bg-gradient-primary" : "bg-gradient-warning") . '">' . $user->role . '</span></td>';
    //             $html .= '<td class="text-center"><span class="text-secondary text-xs font-weight-bold">' . $user->created_at->format('d F Y') . '</span></td>';
            
    //             $html .= '<td class="text-center">';
    //             if ($user->id != Auth::id()) {
    //                 $html .= '<a href="' . route('user-management.edit', $user->id) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">';
    //                 $html .= '<i class="fas fa-user-edit text-secondary"></i>';
    //                 $html .= '</a>';
            
    //                 $html .= '<form id="delete-form-' . $user->id . '" action="' . route('user-management.destroy', $user->id) . '" method="POST" class="d-none">';
    //                 $html .= csrf_field();
    //                 $html .= method_field('DELETE');
    //                 $html .= '</form>';
            
    //                 $html .= '<a class="mx-3" href="javascript:;" title="Delete user" onclick="confirmDelete(' . $user->id . ', \'' . e($user->name) . '\')">';
    //                 $html .= '<i class="fas fa-trash text-danger"></i>';
    //                 $html .= '</a>';
    //             } else {
    //                 $html .= '<span class="mb-0 btn btn-sm bg-gradient-success" style="cursor: default !important;">Logged In</span>';
    //             }
    //             $html .= '</td>';
            
    //             $html .= '</tr>';
    //         }
            
            
    //         $pagination =  $users->links('pagination::bootstrap-5')->toHtml();
            
    //         return response()->json(['html' => $html, 'pagination' => $pagination]);
            
    // }

}
