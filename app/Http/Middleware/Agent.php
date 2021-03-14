<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class Agent
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
        if(!auth()->check())
            return redirect()->route('overview');
            
        $user = auth()->user();

        if($user->hasRole('agent'))
            return $next($request);
        else
            return redirect()->route('overview');
    }
}
