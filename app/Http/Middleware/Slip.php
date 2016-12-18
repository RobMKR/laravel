<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Slip
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
        if (Auth::check() && Auth::user()->getLevel() === 1){
            return $next($request);
        }

        return redirect('/');
    }
}
