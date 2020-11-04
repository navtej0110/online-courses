<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        // default frontend user.
        if ( Auth::guard($guard)->check() && empty($guard) ) {
            return redirect(RouteServiceProvider::HOME);
        }
        
        // admin user.
        if ($guard == "admin" && Auth::guard($guard)->check()) {
            return redirect('/admin/home');
        }

        return $next($request);
    }

}
