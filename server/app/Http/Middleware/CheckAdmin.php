<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Auth;

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
        /*
        $adminId = isset($_COOKIE['adminId']) ? $_COOKIE['adminId'] : null;
        $timestamp = isset($_COOKIE['adminTimestamp']) ? $_COOKIE['adminTimestamp'] : null;
        $adminSig = isset($_COOKIE['adminSig']) ? $_COOKIE['adminSig'] : null;
        $isValid = $adminSig === md5($adminId . 'test' . $timestamp);
        if (!$adminId || !$timestamp || !$adminSig || !$isValid) {
            //return redirect('/signin');
            return response()->json(['errorcode' => 302]);
        }
        */
        if (! Auth::$uid) {
            return response()->json(['errcode' => 302]);
        }
        if (!Auth::$user['is_sys'] && !Auth::$user['proj_id']) {
            return response()->json(['errcode' => 302]);
        }

        return $next($request);
    }

}
