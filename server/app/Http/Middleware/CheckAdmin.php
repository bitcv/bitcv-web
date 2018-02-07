<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        $adminId = isset($_COOKIE['adminId']) ? $_COOKIE['adminId'] : null;
        $timestamp = isset($_COOKIE['adminTimestamp']) ? $_COOKIE['adminTimestamp'] : null;
        $adminSig = isset($_COOKIE['adminSig']) ? $_COOKIE['adminSig'] : null;
        $isValid = $adminSig === md5($adminId . 'test' . $timestamp);
        if (!$adminId || !$timestamp || !$adminSig || !$isValid) {
            return redirect('/#/signin');
        }

        return $next($request);
    }
}
