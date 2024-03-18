<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\CompanyUser;
use App\Models\ProjectRequest;
use App\Models\CompanySubscription;
use Illuminate\Http\Resources\Json\JsonResource;


class WorkSpaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $activeSubscription = CompanySubscription::with('subscription')->where([
            "default" => "yes",
            "status" => "active",
            "payment_status" => "paid",
        ])->first();

        $company_users = CompanyUser::with('user')->whereHas('user', function($q){
            $q->where('user_type', 'admin');
        })->whereCompanyId($this->id)->get();
        
        $admin_users = User::with('roles')->where('user_type', 'admin')->get();
        $data = ['user'=> $admin_users];

        return [
            'company_id' => $this->id,
            'company_name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'todo' => ProjectRequest::where([
                            "company_id" => $this->id,
                            "status" => "todo",
                        ])->count(),
            'on_going' => ProjectRequest::where([
                            "company_id" => $this->id,
                            "status" => "on_going",
                        ])->count(),
            'completed' => ProjectRequest::where([
                            "company_id" => $this->id,
                            "status" => "approved",
                        ])->count(),
            'subscription' => $activeSubscription->subscription,
            'company_users' => $company_users,
            'admin_users' => $data,
            'project_manager' => $this->project_manager,
            'last_request_date' =>  ProjectRequest::whereCompanyId($this->id)->latest()->first()->created_at,
        ];
    }
}
