<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->getLevel() === 1){
                return redirect('slip/home');
            }
            if(Auth::user()->getLevel() === 2){
                return redirect('gift/home');
            }
            if(Auth::user()->getLevel() === 3){
                return redirect('/admin');
            }
                
        }

        return $next($request);
    }
}
