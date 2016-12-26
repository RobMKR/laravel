<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class Gift
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
        if (Auth::check() && (Auth::user()->getLevel() === 2 || Auth::user()->getLevel() === 3)){
            return $next($request);
        }

        return redirect('/');
    }
}
