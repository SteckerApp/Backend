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
            'reference' => $this->id,
            'full_name' => $this->user->first_name. " ". $this->user->last_name,
            'email' => $this->user->email,
            'day_requested' => $this->created_at,
            'day_approved' => $this->approval_date,
            'status' => $this->status,
        ];
    }
}
