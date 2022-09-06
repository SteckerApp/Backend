<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;

class EnsureActiveSubscription
{
    use HandleResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $activeSubscriber = DB::table('company_subscription')::where([
            'user_id'=> auth()->user()->id,
            'company_id'=>getActiveWorkSpace()->id,
            'status'=> 'active',
        ])->where('end_date', '>=', Carbon::now())->exists();

        if ($activeSubscriber) {
            return $next($request);
        }

        return $this->errorResponse(false,"No Active Subscription");
    }
}
