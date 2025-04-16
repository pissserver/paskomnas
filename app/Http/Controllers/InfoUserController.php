<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class InfoUserController extends Controller
{

    public function create()
    {
        return view('laravel-examples/user-profile');
    }

    public function store(Request $request)
    {

        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone' => ['max:50'],
            'location' => ['max:70'],
            'about_me' => ['max:150'],
            'photo' => ['nullable', 'image', 'max:10240'],
        ]);

        if($request->get('email') != Auth::user()->email)
        {
            if(env('IS_DEMO') && Auth::user()->id == 1)
            {
                return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t change the email address.']);
                
            }
            
        }

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
        }
        
        
        User::where('id', Auth::id())->update($dataToUpdate);


        return redirect('/user-profile')->with('success','Profile updated successfully');
    }
}
