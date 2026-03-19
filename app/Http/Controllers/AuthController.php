<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->with('error', 'Sai số điện thoại hoặc mật khẩu');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
