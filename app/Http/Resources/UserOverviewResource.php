<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOverviewResource extends JsonResource
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
            'avatar' => $this->avatar,
            'full_name' => $this->first_name. " ". $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'roles' => $this->roles,
        ];
    }
}
