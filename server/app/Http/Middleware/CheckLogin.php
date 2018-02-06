<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        $userId = isset($_COOKIE['userId']) ? $_COOKIE['userId'] : null;
        $timestamp = isset($_COOKIE['timestamp']) ? $_COOKIE['timestamp'] : null;
        $userSig = isset($_COOKIE['userSig']) ? $_COOKIE['userSig'] : null;
        $isValid = $userSig === md5($userId . 'test' . $timestamp);
        if (!$userId || !$timestamp || !$userSig || !$isValid) {
            return redirect('/#/signin');
        }

        return $next($request);
    }
}
