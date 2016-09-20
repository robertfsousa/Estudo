<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizationToken
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
        $tokenHeader = $request->header('X-Authorization');
        $tokenGet = $request->get('api_key');

        if (!isset(config('options.token')[$tokenHeader]) && (!isset(config('options.token')[$tokenGet])) ) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
