<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Ticket
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
        $level = Auth::user()->getLevel();
        // Only users and SuperAdmin
        if ($level === 0 || $level === 3){
            return $next($request);
        }
        dd($level);
        return redirect('home');
    }
}
