<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfDriver
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::check() &&
            Auth::user()->role === 'driver' &&
            $request->is('admin*') &&
            !$request->is('admin/login')
        ) {
            return redirect()->route('driver.dashboard');
        }

        // if (
        //     Auth::check() &&
        //     Auth::user()->role === 'driver'
        // ) {
        //     return redirect()->route('driver.dashboard');
        // }

        return $next($request);
    }


}