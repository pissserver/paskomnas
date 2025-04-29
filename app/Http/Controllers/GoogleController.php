<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Welcome to dashboard <strong>' . $user->name . '<strong> !');
            }

            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(16)),
                'photo' => $googleUser->getAvatar(),
            ]);

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Your account has been created and you are logged in.');
        } catch (\Exception $e) {
            return redirect('login')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
