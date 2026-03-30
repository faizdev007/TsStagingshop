<?php

namespace App\Http\Middleware;
use Auth;

use Closure;

class Super
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
        if (Auth::check() && Auth::user()->role_id == '1')
        {
            return $next($request);
        }
        elseif (Auth::check() && Auth::user()->role_id == '2')
        {
            $request->session()->flash('message_danger', 'You do not have access to this resource');
            return redirect('/admin');
        }
        else
        {
            $request->session()->flash('message_danger', 'You do not have access to this resource');
            return redirect('/admin');
        }
    }
}
