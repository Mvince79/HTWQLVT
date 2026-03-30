<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect('/admin');
        }

        if ($user && $user->role === 'driver') {
            return redirect()->route('driver.index');
        }

        Auth::logout();
        abort(403, 'Tài khoản không có quyền truy cập.');
    }

}
