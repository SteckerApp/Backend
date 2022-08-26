<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSubscription
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

        $checkSub = $request->user()->companySubscription()
        ->where([
            'company_id' => getActiveWorkSpace()->id, 'status' => 'active', 'payment_status' => 'paid'
            ])->exists();
        if($checkSub) {
            return $next($request);
        }
        return response()->json([
            'message' => 'User subscription expired',
            'reason' => 'subscription'
        ], 308);
    }
}
