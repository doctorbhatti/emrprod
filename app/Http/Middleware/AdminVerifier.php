<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login'); // Redirect to login if not authenticated
        }

        return $next($request); // Proceed with the request if authenticated
    }
}
