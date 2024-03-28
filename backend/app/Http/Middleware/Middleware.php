<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware {
    // @param \Illuminate\Http\Request $request
    // @return string |null

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route("login");
        }
    }

    public function handle($request, Closure $next)
{
    return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', '*')
        ->header('Access-Control-Allow-Credentials', true)
        ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
        ->header('Accept', 'application/json');
}
}