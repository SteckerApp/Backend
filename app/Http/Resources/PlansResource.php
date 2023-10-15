<?php

namespace App\Http\Resources;

use App\Models\CompanySubscription;
use Illuminate\Http\Resources\Json\JsonResource;

class PlansResource extends JsonResource
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
            'subscription_id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'group_identifier' => $this->group_identifier,
            'show_in_catalogue' => $this->visible,
            'most_popular' => $this->most_popular,
            'price' => $this->price,
            'currency' => $this->currency,
            'features' => $this->metadata,
            'type' => $this->type,
            'sales' => CompanySubscription::where('subscription_id', $this->id)->count(),
            'created' => $this->created_at,
            "default_type"=> ($this->default == "1") ? "plan" : "addon",
        ];
    }
}
