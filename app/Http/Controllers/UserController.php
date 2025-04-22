<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::find(Auth::id());
        return view('laravel-examples/user-profile', ['users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone' => ['max:50'],
            'location' => ['max:255'],
            'about_me' => ['max:255'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        $dataToUpdate = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'phone' => $attributes['phone'],
            'location' => $attributes['location'],
            'about_me' => $attributes['about_me'],
        ];

        if ($request->hasFile('photo')) {
            if (Auth::user()->photo && file_exists(public_path(Auth::user()->photo))) {
                unlink(public_path(Auth::user()->photo));
            }

            $file = $request->file('photo');
            $filename = 'user_' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/photos', $filename);
            $dataToUpdate['photo'] = 'storage/photos/' . $filename;
        } elseif ($request->input('reset_photo') == '1') {
            $dataToUpdate['photo'] = null;
        }

        Auth::user()->update($dataToUpdate);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
