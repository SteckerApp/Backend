<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOverviewResource extends JsonResource
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
            'avatar' => $this->user->avatar,
            'phone' => $this->user->phone_number,
            'plan' => $this->subscription->title,
            'workspace' => $this->company->name,
            'project_manager' => $this->company->project_manager->last_name . " " .$this->company->project_manager->first_name ,
            'hear_about_us' => $this->company->hear_about_us
        ];
    }
}
