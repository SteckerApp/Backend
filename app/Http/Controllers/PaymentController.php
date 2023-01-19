<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Trait\HandleResponse;


class PaymentController extends Controller
{
    use HandleResponse;

    public function webhook(Request $request)
    {
        Log::info($request->all());
        dd($request->all());
    }

    public function updatePayment(Request $request)
    {
        $this->validate($request, [
            'subscription_id' => 'required',
            'reference' => 'required',
            'duration' => 'required|in:monthly,bi-annually,annually',
        ]);

        // set end date duration
        if($request->duration == 'monthly'){
            $end_date = Carbon::now()->addMonth();
        }
        elseif($request->duration == 'bi-annually'){
            $end_date = Carbon::now()->addMonth(6);
        }
        elseif($request->duration == 'annually'){
            $end_date = Carbon::now()->addYear();
        }

        //update transaction
      $transaction = Transaction::where([
        'reference' => $request->reference,
        'subscription_id' => $request->subscription_id,
        ])->update([
            'payment_date' => Carbon::now(),
            'start_date' => Carbon::now(),
            'end_date' =>  $end_date,
        ]);

        return $this->successResponse(true , 'Success', 200);

    }
}
