<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role_id) {
                case 1:
                    return redirect('/superadmin/dashboard');
                case 2:
                    return redirect('/companyadmin/dashboard');
                case 3:
                    return redirect('/user/dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            switch ($user->role_id) {
                case 1:
                    return redirect('/superadmin/dashboard');
                case 2:
                    return redirect('/companyadmin/dashboard');
                case 3:
                    return redirect('/user/dashboard');
            }
        } else {
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user) {
                return back()->withErrors(['email' => 'Password is incorrect.']);
            }

            return back()->withErrors(['email' => 'Invalid email address.']);
        }
    }

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login')->with('message', 'You have been logged out.');
    }
}
