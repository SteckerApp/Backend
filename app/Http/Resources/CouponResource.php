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
            'duration' => $diffInMonths,
            'expiration_date' => $this->ends,
            'start_date' => $this->start,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'type' => $this->type,
            'recurring' => $this->recurring,
            'no_of_usage' => $this->no_of_usage,

            'plans' => DB::table('coupon_subscription')
                        ->join('subscriptions', 'coupon_subscription.subscription_id', '=', 'subscriptions.id')
                        ->where([
                            "coupon_subscription.subscription_id" => $this->id
                        ])->get(),
            'billing_types' => DB::table('coupon_subscription')
                        ->join('subscriptions', 'coupon_subscription.subscription_id', '=', 'subscriptions.id')
                        ->where([
                            "coupon_subscription.subscription_id" => $this->id
                        ])
                        ->groupBy('subscriptions.type')
                        ->pluck('subscriptions.type'),
            'redemptions' =>    DB::table('coupon_transaction')
                                ->join('company_subscription', 'company_subscription.reference', '=', 'coupon_transaction.transaction_id')
                                ->where([
                                    "company_subscription.payment_status" => "paid",
                                    "coupon_transaction.coupon_id" => $this->id
                                ])->count(),
        ];
    }
}
