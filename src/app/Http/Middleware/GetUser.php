<?php

namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class GetUser
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function handle(Request $request, Closure $next, ...$guards)
    {
        $request->attributes->add(['id' => Auth::user()->id]);
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
        return $next($request);
    }
}
