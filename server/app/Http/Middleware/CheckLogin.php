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
        $userId = $_COOKIE['userId'];
        $timestamp = $_COOKIE['timestamp'];
        $userSig = $_COOKIE['userSig'];
        $isValid = $userSig === md5($userId . 'test' . $timestamp);
        if (!$userId || !$timestamp || !$userSig || !$isValid) {
            return redirect('/');
        }

        return $next($request);
    }
}
