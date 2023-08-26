<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            // $user = User::find($this->id);
            // $roleNames = $user->getRoleNames();

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => $this->avatar,
            'code' => $this->code,
            'workspaces' => CompanyUser::where('user_id',$this->id)->count(),
            'roles' => $this->getRoleNames(),


          
        ];
    }
}
