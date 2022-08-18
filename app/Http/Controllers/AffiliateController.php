<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\UserBank;
use App\Trait\HandleResponse;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    use HandleResponse;

    public function withdrawal(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required|string',
            'acccount_number' => 'required|string',
            'bank_name' => 'required|string',
        ]);


        $account = UserBank::updateOrCreate(
            [
                'user_id' => $request->user()->id,
            ],
            [
                'account_name' => $request->account_name,
                'acccount_number' => $request->acccount_number,
                'bank_name' => $request->bank_name
            ]
        );

        return $this->successResponse($account, 'bank account store successfully');
    }

    public function history(Request $request)
    {
        
        $referrals = [
        'bank' => $request->user()->bank,
         "history" =>  Affiliate::where("referral_id", $request->user()->id)
            ->where('status', 'paid')
            ->limit(5)
            ->with('user')
            ->get()
        ];
    
        return $this->successResponse($referrals);
    }

    public function requestWithdrawal(Request $request)
    {
        $referrals =  $request->user()->referrals()->where('status', 'active');
        $amount =  $referrals * env('AFFILATE_AMOUNT');

        //bank details


        //make payment




        $referrals->update([
            'status' => 'paid',
            'payment' => $request->user()->bank
        ]);

        return $this->successResponse($referrals);
    }
}
