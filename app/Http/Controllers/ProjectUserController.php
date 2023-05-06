<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectRequest;
use App\Trait\HandleResponse;



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
}
