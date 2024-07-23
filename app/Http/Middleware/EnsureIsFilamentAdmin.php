<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsFilamentAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is_filament_admin is true
        if (Auth::check() && Auth::user()->is_filament_admin) {
            return $next($request);
        }

        // Redirect or abort with 403 Forbidden if not admin
        abort(403, 'Unauthorized');
    }
}
