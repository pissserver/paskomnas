<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use App\Models\User;

class ResetController extends Controller
{
    public function create()
    {
        return view('session/reset-password/sendEmail');
        
    }

    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = User::where('email', $request->email)->first();

        if (!$email) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        return view('session/reset-password/resetPassword', ['email' => $email->email])->with(['success' => 'Email found and reset your password']);
    }
}
