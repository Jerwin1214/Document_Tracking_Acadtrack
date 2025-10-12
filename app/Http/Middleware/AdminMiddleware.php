<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user(); // default web guard

        if ($user && $user->role_id == 1) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}
