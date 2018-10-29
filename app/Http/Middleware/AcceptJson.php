<?php

namespace App\Http\Middleware;

use Closure;

class AcceptJson
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
        // Set header to use json as response.
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
