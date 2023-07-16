<?php

namespace App\Http\Controllers;

use App\Http\Resources\AffiliateOverviewResource;
use App\Http\Resources\CustomerOverviewResource;
use App\Http\Resources\OrderOverviewResource;
use App\Http\Resources\PayoutOverviewResource;
use App\Http\Resources\UserOverviewResource;
use App\Models\Affiliate;
use App\Models\CompanySubscription;
use App\Models\User;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Carbon\Carbon;
use App\Models\Payout;



class AdminOverviewController extends Controller
{
    use HandleResponse;
 
    public function getTotalOverview(Request $request)
    {

        $orders = CompanySubscription::when($request->input('date_from'), function ($query) use ($request) {
                      $query->whereDate('created_at', '>=', Carbon::now());
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::now());
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->count();
        $users = User::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::now());
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::now());
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->count();
        $customers = CompanySubscription::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::now());
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::now());
                    })
                    ->when($request->input('this_month'), function ($query) use ($request) {
                        $query->whereMonth('created_at', Carbon::now()->month);
                    })
                    ->groupBy('user_id')
                    ->count();
        $affiliates = Affiliate::when($request->input('date_from'), function ($query) use ($request) {
                        $query->whereDate('created_at', '>=', Carbon::now());
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::now());
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
                      $query->whereDate('created_at', '>=', Carbon::now());
                    })
                    ->when($request->input('date_to'), function ($query) use ($request) {
                        $query->whereDate('created_at', '<=', Carbon::now());
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
        $users = User::with(['roles'])
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
        $users = CompanySubscription::with(['company','user','subscription'])
                    ->when($request->input('search'), function ($query) use ($request) {
                        $query->where('users.email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('users.last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('reference', 'like', '%' . $request->input('search') . '%');
                    })
                    ->groupBy('user_id')
                    ->paginate($page);
      
       $data = CustomerOverviewResource::collection($users);

        return $this->successResponse($data, 'Operation successful');
    }

    public function getAffiliateOverview(Request $request)
    {
        $page = $request->input('perPage') ?? 10;
        $users = Affiliate::with(['company','user'])
                    ->join('coupons', 'coupons.created_by', 'affiliates.referral_id')
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
