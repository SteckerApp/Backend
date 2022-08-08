<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Carbon\Carbon;
use App\Trait\HandleResponse;




class UserSubscriptionController extends Controller
{
    use HandleResponse;
    public function attachUserSubscription(Request $request)
    {
        $this->validate($request, [
            'subscription_id' => 'required',
            // 'reference' => 'required',
            'duration' => 'required|in:monthly,bi-annually,annually',
        ]);

        $record = UserSubscription::create([
            'user_id' => auth()->user()->id,
            'subscription_id' => $request->subscription_id,
            'duration' => $request->duration,
            'payment_date' => Carbon::now(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
            'payment_status' => 'pending',
            'status' => 'inactive',

        ]);

        return $this->successResponse($record, 'User Subscription created successfully', 201);

    }
    public function activatePayment(Request $request, $id)
    {
        $this->validate($request, [
            'reference' => 'required',
        ]);
        $record = UserSubscription::find($id);
        $record->reference = $request->reference;
        $record->payment_status = "paid";
        $record->status = "active";

        $record = $record->save();

        return $this->successResponse($record, 'User Subscription activated successfully', 200);
    }
}
