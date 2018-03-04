<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Auth;

class AutoLogin
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
        Auth::checkLogin();

        return $next($request);
    }

}
