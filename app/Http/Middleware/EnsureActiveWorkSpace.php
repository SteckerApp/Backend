<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveWorkSpace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authUser = $request->user();

        if (!getActiveWorkSpace() && $authUser->user_type == 'client') {

            $workspaces = $authUser->companies;

            $current = $workspaces->shift();

            setActiveWorkSpace($current, true);
        }
        
        return $next($request);
    }
}
