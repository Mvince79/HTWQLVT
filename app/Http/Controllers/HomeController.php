<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        if ($user->role === 'driver') {
            return redirect()->route('driver.index');
        }

        abort(403);
    }
}
