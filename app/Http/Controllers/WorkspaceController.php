<?php

namespace App\Http\Controllers;

use App\Models\AdminCompany;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;

class WorkspaceController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $authUser = auth()->user();
        $workspacePermission = collect([]);


        if (
            in_array($authUser->user_type, config('auth.admin_middle'))
        ) {
            // pm and designer
            $requestDetails = AdminCompany::where('user_id', $authUser->id)->with([
                'company:id,name', 'company.allCompanyRequest', 'company.activeDefaultSubscripition',
                'company.pm.user', 'company.designer.user', 'company.owner'
            ])->latest()->paginate();

        } else if (
            in_array($authUser->user_type, config('auth.admin'))
        ) {

            // pm and designer
            $requestDetails = AdminCompany::with([
                'company:id,name', 'company.allCompanyRequest', 'company.activeDefaultSubscripition',
                'company.pm.user', 'company.designer.user', 'company.owner'
            ])->latest()->paginate();
        }

        $pagination = $requestDetails->toArray();
        $pagination['data'] = null;
        $workspacePermission->push($pagination);
        
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


        return $this->successResponse($workspacePermission);
    }


    public function list(Request $request)
    {
        $this->validate($request, [
            'company' => 'required'
        ]);
        $perPage = ($request->perPage) ?? 15;
        $projects = Company::where('id', $request->company)->first()->allCompanyRequest()->with(['pm', 'designer', 'uploadedFiles:id']);
        ($request->todo) ? $projects =  $projects->where('brands.status', 'pending') : "";
        ($request->ongoing) ? $projects =  $projects->where('brands.status', 'on-going') : "";
        ($request->in_review) ? $projects =  $projects->where('brands.status', 'designer-approved')->orwhere('brands.status', 'pm-approved') : "";
        ($request->approved) ? $projects =  $projects->where('brands.status', 'completed') : "";
        ($request->page) ? $projects =  $projects->paginate($perPage) : $projects = $projects->get();
        // dd($request->ongoing);
        return $this->successResponse($projects, '', 200);
    }
}
