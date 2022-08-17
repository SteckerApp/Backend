<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Company;
use App\Models\ProjectRequest;
use App\Trait\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    use HandleResponse;

    public function home(){

        $authUser = auth()->user();
        //set workspace
        $workspaces = $authUser->companies;

        $workspaces->each(function($workspace){
            $workspace->staffs = $workspace->users()->limit(5)->get();
            $workspace->staffs_strength = $workspace->users()->count();
            return $workspace;
        });


        $current = $workspaces->shift();

        setActiveWorkSpeace($current, true);

        // personal details
        $personal = [
            'first_name' => $authUser->first_name,
            'last_name'  => $authUser->last_name,
            'company_role' => $current->role,
            'workspace' =>  $current,
            'other_workspace' => $workspaces
        ];

        //product manager
        $projects = ProjectRequest::latest()->limit(5)->get();

        $projects->each(function($project){
            $project->revivsion_count = $project->uploadedFiles()->count();
        });

        $stat = [
            'todo' => $projects->where('status', 'pending')->count(),
            'ongoing' => $projects->where('status', 'on-going')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
        ];

        // workspace details




        // if admin
        $projects = ProjectRequest::query();
        $workspaces = Company::count();
        $brands = Brand::count();
        
        $latest = Company::latest()->limit(6)->with("users.userSubscription")->get();
        $latest->each(function($record){
            $allRequest = $record->brands()->requests();
            $allRequest->each(function($singleReq){
                return $singleReq->groupBy('status')->count();
            });
        });

        $stat = [
            'todo' => $projects->where('status', 'pending')->count(),
            'ongoing' => $projects->where('status', 'on-going')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
        ];

        return $this->successResponse($personal);
    }
}
