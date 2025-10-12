<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
{
    if (auth()->check() && auth()->user()->role_id == 3) {
        return $next($request);
    }

    return redirect('/login')->with('error', 'Unauthorized access.');
}

}
