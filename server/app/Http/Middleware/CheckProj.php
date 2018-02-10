<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Auth;

class CheckProj
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
        $projId = $request->input('projId');
        if (!$projId || Auth::$user['is_sys']) {
            return $next($request);
        }
        if (! Auth::$uid || Auth::$user['proj_id'] != $projId) {
            return response()->json(['errcode' => 302]);
        }

        return $next($request);
    }

}
