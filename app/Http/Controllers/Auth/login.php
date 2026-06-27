<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class login extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
            if (auth()->user()->active == 1) {
                                return redirect()->route('main');

            }else {
                Auth::guard('web')->logout();
                return redirect()->route('login')->with('error', 'Your account is inactive or deleted');
            }
        } else {
            return redirect()->route('login')->with('error', 'Invalid username or password');
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
