<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
       
        $startDate = Carbon::parse( $this->start);
        $endDate = Carbon::parse($this->ends);

        $diffInMonths = $startDate->diffInMonths($endDate);
      

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'duration' => $diffInMonths . " month(s)",
            'expiration_date' => $this->ends,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'type' => $this->type,
            'redemptions' =>    DB::table('coupon_transaction')
                                ->join('company_subscription', 'company_subscription.reference', '=', 'coupon_transaction.transaction_id')
                                ->where([
                                    "company_subscription.payment_status" => "paid",
                                    "coupon_transaction.coupon_id" => $this->id
                                ])->count(),
        ];
    }
}
