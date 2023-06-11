<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\UserBank;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;

class AffiliateController extends Controller
{
    use HandleResponse;

    public function withdrawal(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'bank_code' => 'required|string',
        ]);

        $account = UserBank::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'account_name' => $request->account_name,
                'acccount_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code

            ]
        );


        return $this->successResponse($account, 'bank account store successfully');
    }

    public function history(Request $request)
    {
        
        $referrals = [
        'bank' => $request->user()->bank,
         "referral_history" =>  Affiliate::where("referral_id", $request->user()->id)
            ->where('status', 'paid')
            ->when($request->input('limit'), function ($query) use ($request) {
                $query->limit($request->input('limit'));
            })
            ->with('user')
            ->latest()
            ->get(),

        "payment_history" =>  Payout::where("user_id", $request->user()->id)
            ->when($request->input('limit'), function ($query) use ($request) {
                $query->limit($request->input('limit'));
            })
            ->latest()
            ->get()
        ];
    
        return $this->successResponse($referrals);
    }

    public function balance(Request $request)
    {
        
        $referrals =  $request->user()->referrals;

        $balance = $referrals->where('status', 'paid')->count() * env('AFFILATE_AMOUNT');

        $data = [
            'balance' => $balance
        ];
        return $this->successResponse($data);
    }

    public function requestWithdrawal(Request $request)
    {
        $referrals =  $request->user()->referrals()->where('status', 'paid');
        $withdrawal_amount =  (clone $referrals)->count() * env('AFFILATE_AMOUNT');

        Payout::create([
            'user_id'=> $request->user()->id,
            'amount'=> $withdrawal_amount,
        ]);

        (clone $referrals)->update([
            'status' => 'pending',
        ]);

        return $this->successResponse(true);
    }
}
