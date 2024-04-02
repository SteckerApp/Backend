<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $workspaces = $this->allAttachedCompany;
        $current = $workspaces->shift();
        return [
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'currency' => $this->currency,
            'avatar' => $this->avatar,
            'user_type' => $this->user_type,
            'email_notification' => $this->email_notification,
            'desktop_notification' => $this->desktop_notification,
            'email_verified_at' => $this->email_verified_at,
            'notification' => $this->notification,
            'user_identification' => $this->id,
            'loggin_company' => $current
        ];
    }
}
