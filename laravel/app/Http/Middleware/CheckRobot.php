<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

/**
 * Class CheckRobot
 * @package App\Http\Middleware
 */
class CheckRobot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = new Agent();
        if ($agent->isRobot()) {
            return response()->json([], 403);
        }

        return $next($request);
    }
}
