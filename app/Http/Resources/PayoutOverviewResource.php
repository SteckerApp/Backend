<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayoutOverviewResource extends JsonResource
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
            'payout_id' => $this->id,
            'full_name' => $this->user->first_name. " ". $this->user->last_name,
            'email' => $this->user->email,
            'avatar' => $this->user->avatar,
            'amount' => $this->amount,
            'day_requested' => $this->created_at,
            'day_approved' => $this->approval_date,
            'payment_mode' => "Paystack",
            'status' => $this->status,
        ];
    }
}
