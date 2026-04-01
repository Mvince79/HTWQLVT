<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/admin/login');
        }

        if ($user->role === 'admin') {
            return redirect('/admin'); 
        }

        if ($user->role === 'driver') {
            return redirect()->route('driver.dashboard');
        }

        Auth::logout();
        return redirect('/admin/login');
    }

}
