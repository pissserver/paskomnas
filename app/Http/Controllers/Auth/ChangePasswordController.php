<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('session/reset-password/resetPassword', [
            'email' => $request->email,
        ]);
    }
    
    public function changePassword(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $dataToUpdate = [
            'password' => Hash::make($request->password),
        ];

        $user = User::where('email', $request->email)->first();

        $user->update($dataToUpdate);

    
        return redirect()->route('login')->with('success', 'Password berhasil diubah');
    }
}
