<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;
use Illuminate\Support\Facades\DB;



class ProjectUserController extends Controller
{
    use HandleResponse;

    public function addProjectUser(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'users' => 'required|array',
            'users.*' => 'numeric',
        ]);
   
        // First, retrieve the project instance
        $project = ProjectRequest::findOrFail($request->project_id);

        // Next, sync the user IDs with the project instance
        $project->users()->sync($request->users);

        return $this->successResponse('','Users Added Succesfully', 200);
    }

    public function getProjectTeam(Request $request, $project_id)
    {
       
        $project = ProjectRequest::findOrFail($project_id);



        // First, retrieve the project instance
        // $project_users = User::whereHas('projectUser', function($q) use($project_id){
        //     $q->where('project_id', $project_id);
        // })->get();

        $company_users = User::whereHas('companyUser', function($q) use($project){
            $q->where('company_id', $project->company_id);
        })->get();
        //remove pm since pm is already part of project users
        $users = User::where('user_type','admin')->whereNotIn('id', [$project->pm])->get();

        $result = $company_users->merge($users);
        

        return $this->successResponse( $result, 'Users fetched successfully', 200);
    }
}
