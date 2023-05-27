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
                $tranx = Transaction::where('reference', $reference)->with('cart')->firstOrFail();
            //Check if subscription id = 1 which is just a charge for change of card
                if($tranx->subscription_id == 1){
                    //update card authorization
                    Company::whereId($tranx->company_id)->update([
                        "card_authorization" => $request->input('data.authorization')
                    ]);

                    return response()->json(['success' => true]);
                }

                $subscription = Subscription::where('id',$tranx->subscription_id)->first();

               // set end date duration
                if($subscription->type == 'monthly'){
                    $qty = $tranx->unit * 1;
                    $end_date = Carbon::now()->addMonth($qty);
                }
                elseif($subscription->type == 'quarterly'){
                    $qty = $tranx->unit * 3;
                    $end_date = Carbon::now()->addMonth($qty);
                }
                elseif($subscription->type == 'bi-annually'){
                    $qty = $tranx->unit * 6;
                    $end_date = Carbon::now()->addMonth($qty);
                }
                elseif($subscription->type == 'annually'){
                    $qty = $tranx->unit * 1;
                    $end_date = Carbon::now()->addYear($qty);
                }

                CompanySubscription::create([
                    'reference'=> $tranx->reference,
                    'user_id'=> $tranx->cart->user_id,
                    'subscription_id'=> $tranx->subscription_id,
                    'company_id'=> $tranx->company_id,
                    'payment_date'=> Carbon::now(),
                    'start_date'=> Carbon::now(),
                    'end_date'=> $end_date,
                    'type'=> $subscription->type,
                    'payment_status'=> 'paid',
                    'status'=> 'active',
                ]);
                
                //update card authorization

                Company::whereId($tranx->company_id)->update([
                    "card_authorization" => $request->input('data.authorization')
                ]);

                
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
