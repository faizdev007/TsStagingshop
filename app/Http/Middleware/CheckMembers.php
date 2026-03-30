<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckMembers
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
        $is_enabled = settings('members_area');

        if($is_enabled == 0)
        {
            Log::info('A user tried to access the members area, when not enabled');

            if(Auth::user())
            {
                return redirect('/admin');
            }
            else
            {
                return redirect('/');
            }
        }
        else
        {
            if(Auth::user())
            {
                // Share Unread Messages to Member View...
                $unread = Message::where('to_id', Auth::user()->id)->where('message_read', 'n')->count();

                view()->share('unread', $unread);
            }

            return $next($request);
        }
    }
}
