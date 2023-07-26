<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\Payout;



class PayoutController extends Controller
{
    use HandleResponse;

    public function approvePayout(Request $request)
    {
       $payout = Payout::whereId($request->input('payout_id'))->firstOrFail();

       $payout->status = 'completed';

       $payout->save();

       return $this->successResponse($payout, 'Payout Approved Succesfully');

    }

    public function declinePayout(Request $request)
    {
       $payout = Payout::whereId($request->input('payout_id'))->firstOrFail();

       $payout->status = 'declined';

       $payout->save();

       return $this->successResponse($payout, 'Payout Declined Succesfully');

    }
}
