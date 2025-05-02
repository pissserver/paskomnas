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
            return redirect('dashboard')->with(['success'=>"Anda sudah login, silakan akses sistem PASKOMNAS PISS."]);
        }

        return view('session.login');
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
            return redirect('dashboard')->with(['success'=>'Selamat datang <strong>' . $name . '</strong> di sistem PASKOMNAS PISS.']);
        }
        else{

            return back()->withErrors(['email'=>'Email atau password salah!']);
        }
    }
    
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->update(['last_active_at' => null]);
        }

        Auth::logout();

        return redirect('/login')->with(['success'=>'Berhasil keluar, silakan login kembali untuk akses sistem!']);
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
            return redirect('/dashboard')->withErrors(['password' => 'Beralih akun gagal, password salah!']);
        }

        Auth::logout();

        Auth::login($targetUser);

        session()->regenerate();

        return redirect('/dashboard')->with('success', 'Beralih akun ke <strong>' . $targetUser->name . '</strong> berhasil.');

    }
}
