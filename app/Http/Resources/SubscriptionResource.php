<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            "id"=> $this->id,
            "title"=> $this->title,
            "description"=> $this->description,
            "price"=> $this->price,
            "type"=> $this->type,
            "days"=> $this->days,
            "metadata"=> $this->metadata,
            "default_type"=> ($this->default == "1") ? "plan" : "addon",
            "info"=> $this->info,
            "discounted"=> $this->discounted,
            "currency"=> $this->currency,
            "visible"=> $this->visible,
            "most_popular"=> $this->most_popular,
            "order"=> $this->order,
            "user_limit"=> $this->user_limit,
            "design_limit"=> $this->design_limit,
            "category"=> $this->category,
            "group_identifier"=> $this->group_identifier,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
            "save_up_to"=>$this->save_up_to
        ];
    }
}
