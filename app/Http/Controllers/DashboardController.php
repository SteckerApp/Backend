<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\AdminCompany;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;
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
            $workspaces = $authUser->allAttachedCompany;

            $workspaces->each(function ($workspace) {
                $workspace->staffs = $workspace->users()->limit(5)->get();
                $workspace->staffs_strength = $workspace->users()->count();
                return $workspace;
            });

            $current = $workspaces->shift();

            setActiveWorkSpace($current, true);
            $current->owner = CompanyUser::where([
                'user_id'=> $authUser->id,
                'company_id'=> getActiveWorkSpace()->id,
            ])->first()->company->owner->first();


            $userRole = DB::table('company_user')->where(['company_id' => getActiveWorkSpace()->id, 'user_id' => $authUser->id])->first();

            // personal details
            $personal = [
                'first_name' => $authUser->first_name,
                'last_name'  => $authUser->last_name,
                'avatar'  => $authUser->avatar,
                'email'  => $authUser->email,
                'phone_number'  => $authUser->phone_number,
                'company_role' => $userRole->role,
                'workspace' =>  $current,
                'other_workspace' => $workspaces
            ];

            //product manager
            $projects = ProjectRequest::latest()->with('pm')->with('projectUser:id,avatar')->limit(5)->get();

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
                'projects' => $projects,

            ];
        } else if (
            in_array($authUser->user_type, config('auth.admin_middle'))
        ) {
            // pm and designer
            $workspacePermission = collect([]);

            $requestDetails = AdminCompany::where('user_id', $authUser->id)->with([
                'company:id,name', 'company.allCompanyRequest', 'company.activeDefaultSubscripition',
                'company.pm.user', 'company.designer.user', 'company.owner'
            ])->latest()->limit(6)->get();

            $requestDetails = $requestDetails->each(function ($company) use ($workspacePermission) {
                $workspacePermission->push([
                    'company_id' => $company->company->id,
                    'company_name' => $company->company->name,

                    'company_subscription' => $company->company->activeDefaultSubscripition()->first(),
                    'company_stats' => [
                        "todo" => collect($company->company->toArray()['all_company_request'])->where('status', 'pending')->count(),
                        "ongoing" => collect($company->company->toArray()['all_company_request'])->where('status', 'ongoing')->count(),
                        "completed" => collect($company->company->toArray()['all_company_request'])->where('status', 'completed')->count()
                    ],
                    'company_pm' => $company->company->pm,
                    "company_desinger" => $company->company->designer,
                    "company_owner" => $company->company->owner->shift()
                ]);
            });


            $projects = ProjectRequest::where('pm_id', $authUser->id);

            $stat = [
                'todo' => $projects->where('status', 'pending')->count(),
                'ongoing' => $projects->where('status', 'on-going')->count(),
                'completed' => $projects->where('status', 'completed')->count(),
            ];

            $workspaces = $authUser->companies()->count();

            $brands = $authUser->brands->count();

            $response = [
                'statistics' => $stat,
                'workspaces' => $workspaces,
                'brands' => $brands,
                'histroy' => $workspacePermission
            ];
        } else if (
            in_array($authUser->user_type, config('auth.admin'))
        ) {

            // pm and designer

            $workspacePermission = collect([]);

            $requestDetails = AdminCompany::with([
                'company:id,name', 'company.allCompanyRequest', 'company.activeDefaultSubscripition',
                'company.pm.user', 'company.designer.user', 'company.owner'
            ])->latest()->limit(6)->get();


            $requestDetails = $requestDetails->each(function ($company) use ($workspacePermission) {
                $workspacePermission->push([
                    'company_id' => $company->company->id,
                    'company_name' => $company->company->name,
                    'company_subscription' => $company->company->activeDefaultSubscripition()->first(),
                    'company_stats' => [
                        "todo" => collect($company->company->toArray()['all_company_request'])->where('status', 'pending')->count(),
                        "ongoing" => collect($company->company->toArray()['all_company_request'])->where('status', 'ongoing')->count(),
                        "completed" => collect($company->company->toArray()['all_company_request'])->where('status', 'completed')->count()
                    ],
                    'company_pm' => $company->company->pm,
                    "company_desinger" => $company->company->desinger,
                    "company_owner" => $company->company->owner->shift()
                ]);
            });


            $projects = ProjectRequest::all();

            $stat = [
                'todo' => $projects->where('status', 'pending')->count(),
                'ongoing' => $projects->where('status', 'on-going')->count(),
                'completed' => $projects->where('status', 'completed')->count(),
            ];

            $workspaces = Company::count();

            $brands = Brand::count();

            $response = [
                'statistics' => $stat,
                'workspaces' => $workspaces,
                'brands' => $brands,
                'histroy' => $workspacePermission
            ];
        }

        return $this->successResponse($response);
    }

    public function setWorkspace(Request $request, $company_id)
    {

        $company = Company::whereId($company_id)->first();

        setActiveWorkSpace($company, true);

        return $this->successResponse($company, "Workspace activated successfully");
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
