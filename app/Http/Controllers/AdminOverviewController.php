<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payout;
use App\Models\Company;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;
use Illuminate\Support\Facades\DB;
use App\Models\CompanySubscription;
use App\Http\Resources\WorkSpaceResource;
use App\Http\Resources\UserOverviewResource;
use App\Http\Resources\OrderOverviewResource;

use App\Http\Resources\PayoutOverviewResource;
use App\Http\Resources\CustomerOverviewResource;
use App\Http\Resources\AffiliateOverviewResource;
use App\Models\CompanyUser;

class AdminOverviewController extends Controller
{
    use HandleResponse;

    public function getTotalOverview(Request $request)
    {

        $todo = ProjectRequest::where('status','todo')->count();
        $on_going = ProjectRequest::where('status','on_going')->count();
        $approved = ProjectRequest::where('status','approved')->count();
        $total_workspace = Company::count();
        $total_board = ProjectRequest::count();

        $new_projects = Company::whereHas('projects', function($q){
                            $q->where('status', 'todo');
                        });
        if($request->user()->hasPermissionTo('view all workspace')){
            $new_projects =  $new_projects->get();
        }
        else{
            $companies = CompanyUser::where('user_id', $request->user()->id)->groupBy('company_id')->pluck('company_id')->toArray();
            $new_projects =  $new_projects->whereIn('id',  $companies)->get();
        }

       $data = [
            "todo" => $todo,
            "on_going" => $on_going,
            "approved" => $approved,
            "total_workspace" => $total_workspace,
            "total_board" => $total_board,
            "new_projects" => WorkSpaceResource::collection($new_projects)

        ];

        return $this->successResponse($data, 'Operation successful');
    }
 
    public function getTotalAnalytics(Request $request)
    {

        $orders = CompanySubscription::when($request->input('date_from'), function ($query) use ($request) {
                      $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->count();
        $users = User::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->count();
        $customers = CompanySubscription::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->groupBy('user_id')
                    ->count();
        $affiliates = Affiliate::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->count();
       $data = [
            "total_orders" => $orders,
            "total_users" => $users,
            "total_customers" => $customers,
            "total_affiliates" => $affiliates,
        ];

       

        return $this->successResponse($data, 'Operation successful');
    }

    public function getOrderOverview(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $orders = CompanySubscription::with(['user','subscription','company'])
                    ->when($request->input('date_from'), function ($query) use ($request) {
                      $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('reference', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->input('search') . '%');
                    })
                    ->paginate($page);

      
       $data = OrderOverviewResource::collection($orders);

        return $this->successResponse($data, 'Operation successful');
    }

    public function getUserOverview(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $users = User::with(['roles', 'companies:id,name'])
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('roles.name', 'like', '%' . $request->input('search') . '%');
                    })
                    ->paginate($page);
      
       $data = UserOverviewResource::collection($users);

        return $this->successResponse($data, 'Operation successful');
    }

    public function getCustomerOverview(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $users = CompanySubscription::with(['company.project_manager','user','subscription:id,title'])
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('users.email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference', 'like', '%' . $request->input('search') . '%');
                    })
                    // ->select('company_subscription.user_id', DB::raw('COUNT(*) as subscription_count'))
                    ->groupBy('user_id')
                    ->paginate($page);
      
       $data = CustomerOverviewResource::collection($users);

        return $this->successResponse($data, 'Operation successful');
    }

    public function getAffiliateOverview(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $users = Affiliate::with(['company','user'])
                    // ->join('coupons', 'coupons.created_by', 'affiliates.referral_id')
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('users.email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference', 'like', '%' . $request->input('search') . '%');
                    })
                    ->groupBy('user_id')
                    ->paginate($page);
      
       $data = AffiliateOverviewResource::collection($users);

        return $this->successResponse($data, 'Operation successful');
    }

    public function getPayoutHistory(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $users = Payout::with(['user'])
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('users.email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference', 'like', '%' . $request->input('search') . '%');
                    })
                    ->paginate($page);
      
       $data = PayoutOverviewResource::collection($users);

        return $this->successResponse($data, 'Operation successful');
    }


}
