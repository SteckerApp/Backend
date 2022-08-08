<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Carbon\Carbon;
use App\Trait\HandleResponse;




class UserSubscriptionController extends Controller
{
    use HandleResponse;
    public function store(Request $request)
    {
        $this->validate($request, [
            'subscription_id' => 'required',
            'reference' => 'required',
            'duration' => 'required|in:monthly,bi-annually,annually',
        ]);

        $record = UserSubscription::create([
            'user_id' => auth()->user()->id,
            'subscription_id' => $request->subscription_id,
            'reference' => $request->reference,
            'duration' => $request->duration,
            'payment_date' => Carbon::now(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
            'status' => 'active',
        ]);

        return $this->successResponse($record, 'User Subscription created successfully', 201);

    }
}
