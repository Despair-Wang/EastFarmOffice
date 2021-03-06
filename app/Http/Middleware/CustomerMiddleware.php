<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // return $next($request);
        if (Auth::check()) {
            $user = Auth::guard('customer')->user();
            if ($user->Auth == $role) {
                return $next($request);
            } else {
                return redirect()->intended('unfound');
            }
        } else {
            return redirect()->intended('login');
        }

    }
}