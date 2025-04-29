<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class SessionsController extends Controller
{
    public function create()
    { 
        if (Auth::check()) {
            return redirect('dashboard')->with(['success'=>"You're already logged in."]);
        }

        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required' 
        ]);

        if(Auth::attempt($attributes))
        {
            $name = Auth::user()->name;
            session()->regenerate();
            return redirect('dashboard')->with(['success'=>'Welcome to dashboard <strong>' . $name . '<strong> !']);
        }
        else{

            return back()->withErrors(['email'=>'Email or password invalid.']);
        }
    }
    
    public function destroy()
    {
        Auth::logout();

        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }

    public function switchAccount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required',
        ]);

        $currentUser = Auth::user();

        $targetUser = User::findOrFail($request->user_id);

        if (!Hash::check($request->password, $targetUser->password)) {
            return back()->withErrors(['password' => 'Switch account failed :(']);
        }

        Auth::logout();

        Auth::login($targetUser);

        session()->regenerate();

        return redirect('/dashboard')->with('success', 'Switch account successfully to <strong>' . $targetUser->name . '</strong> ');

    }
}
