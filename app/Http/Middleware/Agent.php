<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Agent
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
        // Agent, Admin & Superuser Can Access...
        if (Auth::check() && Auth::user()->role_id <= '3')
        {
            return $next($request);
        }
        else
        {
            $request->session()->flash('message_danger', 'You do not have access to this resource');
            return redirect('/admin');
        }
    }
}
