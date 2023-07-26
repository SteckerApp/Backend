<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Affiliate;


class AffiliateOverviewResource extends JsonResource
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
        return [
            'full_name' => $this->user->first_name. " ". $this->user->last_name,
            'email' => $this->user->email,
            'referral_code' => $this->code,
            'total_commission' => Affiliate::where("user_id", $this->user_id)
                                    ->count() * env('AFFILATE_AMOUNT'),
            'unpaid_commission' => Affiliate::where("user_id", $this->user_id)
                                    ->where('status', 'pending')
                                    ->count() * env('AFFILATE_AMOUNT'),
        ];
    }
}
