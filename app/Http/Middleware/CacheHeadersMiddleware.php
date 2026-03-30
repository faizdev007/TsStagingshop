<?php

namespace App\Http\Middleware;

use Closure;

class CacheHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Cache-Control', 'public, max-age=31536000'); // 1 year

        return $response;
    }
}
