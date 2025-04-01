<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CompanyAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id === 2) {
            return $next($request);
        }
        return redirect('/login')->with('error', 'Access denied!');
    }
}
