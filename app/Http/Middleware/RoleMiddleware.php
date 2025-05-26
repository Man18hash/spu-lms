<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (! Auth::check() || Auth::user()->role !== $role) {
            Auth::logout();
            return redirect()->route("{$role}.login");
        }
        return $next($request);
    }
}
