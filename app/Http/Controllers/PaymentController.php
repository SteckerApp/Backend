<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\DB;
use App\Models\CompanySubscription;
use Illuminate\Support\Facades\Log;



class PaymentController extends Controller
{
    use HandleResponse;

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        if ($this->isValidSignature($payload, $signature)) {
            $event = $request->input('event');
            $reference = $request->input('data.reference');

            // Process the webhook event and data

            if($event == "charge.success"){
                // Log::error($request->input('data.reference'));
                $tranx = CompanySubscription::where('reference', $reference)->firstOrFail();

                $subscription = Subscription::where('id',$tranx->subscription_id)->firstOrFail();

                // set end date duration
                if($subscription->type == 'monthly'){
                    $end_date = Carbon::now()->addMonth();
                }
                elseif($subscription->type == 'quarterly'){
                    $end_date = Carbon::now()->addMonth(3);
                }
                elseif($subscription->type == 'bi-annually'){
                    $end_date = Carbon::now()->addMonth(6);
                }
                elseif($subscription->type == 'annually'){
                    $end_date = Carbon::now()->addYear();
                }

                $tranx->payment_date = Carbon::now();
                $tranx->start_date = Carbon::now();
                $tranx->end_date = $end_date;
                $tranx->payment_status = 'paid';
                $tranx->status = 'active';

                $tranx->save();
            }
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }

    private function isValidSignature($payload, $signature)
    {
        $secret = config('paystack.secret_key');
        $hash = hash_hmac('sha512', $payload, $secret);

        return $hash === $signature;
    }
}
