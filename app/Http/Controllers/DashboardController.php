<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Company;
use App\Models\ProjectRequest;
use App\Trait\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    use HandleResponse;

    public function home(Request $request)
    {

        $authUser = auth()->user();

        if ($authUser->user_type == 'client') {

            //set workspace
            $workspaces = $authUser->companies;

            $workspaces->each(function ($workspace) {
                $workspace->staffs = $workspace->users()->limit(5)->get();
                $workspace->staffs_strength = $workspace->users()->count();
                return $workspace;
            });


            $current = $workspaces->shift();

            setActiveWorkSpace($current, true);

            $userRole = DB::table('company_user')->where(['company_id' => getActiveWorkSpace()->id, 'user_id' => $authUser->id])->first();

            // personal details
            $personal = [
                'first_name' => $authUser->first_name,
                'last_name'  => $authUser->last_name,
                'company_role' => $userRole->role,
                'workspace' =>  $current,
                'other_workspace' => $workspaces
            ];

            //product manager
            $projects = ProjectRequest::latest()->with('pm')->limit(5)->get();

            $projects->each(function ($project) {
                $project->file_uploads = $project->uploadedFiles()->count();
            });

            $stat = [
                'todo' => $projects->where('status', 'pending')->count(),
                'ongoing' => $projects->where('status', 'on-going')->count(),
                'completed' => $projects->where('status', 'completed')->count(),
            ];


            $response = [
                'statistics' => $stat,
                'personal' => $personal,
                'projects' => $projects
            ];
        } else if (
            in_array($authUser->user_type, config('auth.admin_middle'))
        ) {
            // pm and designer
            $projects = ProjectRequest::where('pm_id', $authUser->id);

            $stat = [
                    'todo' => $projects->where('status', 'pending')->count(),
                    'ongoing' => $projects->where('status', 'on-going')->count(),
                    'completed' => $projects->where('status', 'completed')->count(),
                ];
            
                $workspaces = $authUser->companies()->count();

                $brands = $authUser->brands->count();


                $recents = ProjectRequest::where('pm_id', $authUser->id)->with(['pm', 'user'])->latest()->limit(6)->get();
                $recents->each(function ($project) {
                    $project->file_uploads = $project->uploadedFiles()->count();
                });

                $response = [
                    'statistics' => $stat,
                    'workspaces' => $workspaces,
                    'brands' => $brands,
                    'histroy' => $recents
                ];

        }else if (
            in_array($authUser->user_type, config('auth.admin'))
        ){


            // pm and designer
            $projects = ProjectRequest::all();

            $stat = [
                    'todo' => $projects->where('status', 'pending')->count(),
                    'ongoing' => $projects->where('status', 'on-going')->count(),
                    'completed' => $projects->where('status', 'completed')->count(),
                ];
            
                $workspaces = Company::count();

                $brands = Brand::count();


                $recents = ProjectRequest::with(['pm', 'user'])->latest()->limit(6)->get();
                $recents->each(function ($project) {
                    $project->file_uploads = $project->uploadedFiles()->count();
                });

                $response = [
                    'statistics' => $stat,
                    'workspaces' => $workspaces,
                    'brands' => $brands,
                    'histroy' => $recents
                ];
        }

        
        
        
        // $brands = Brand::count();

        // $latest = Company::latest()->limit(6)->with("users.userSubscription")->get();
        // $latest->each(function($record){
        //     $allRequest = $record->brands()->requests();
        //     $allRequest->each(function($singleReq){
        //         return $singleReq->groupBy('status')->count();
        //     });
        // });

        // $stat = [
        //     'todo' => $projects->where('status', 'pending')->count(),
        //     'ongoing' => $projects->where('status', 'on-going')->count(),
        //     'completed' => $projects->where('status', 'completed')->count(),
        // ];

        return $this->successResponse($response);
    }

    public function affilate(Request $request)
    {
        $referrals =  $request->user()->referrals;
        $count = $referrals->count();
        $total = $referrals->where('status', '!=', 'pending')->count() * env('AFFILATE_AMOUNT');
        $ready = $referrals->where('status', 'active')->count() * env('AFFILATE_AMOUNT');
        $pending = $referrals->where('status', 'pending')->count() * env('AFFILATE_AMOUNT');
        $paid = $referrals->where('status', 'paid')->count() * env('AFFILATE_AMOUNT');

        $code = $request->user()->referral_code;

        return $this->successResponse([
            'total_referral' => $count,
            'total_payment' => $total,
            'pending_payment' => $ready,
            'total_paid' => $paid,

            'referral_code' => $code
        ]);
    }
}
