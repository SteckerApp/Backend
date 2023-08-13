<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderOverviewResource extends JsonResource
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
            'order_number' => $this->id,
            'reference' => $this->reference,
            'full_name' => $this->user->first_name. " ". $this->user->last_name,
            'email' => $this->user->email,
            'avatar' => $this->user->avatar,
            'plan' => $this->subscription->title,
            'status' => $this->status,
            'workspace' => $this->company->name,
            'end_date' => $this->end_date,
            'fee' => $this->subscription->price,
            'currency' => $this->subscription->currency,

        ];
    }
}
