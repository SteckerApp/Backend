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

    public function getMonthOrderStats(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $rev_naira = CompanySubscription::
            whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->where('payment_status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'NGN');
            })
            ->count();
        $rev_dollar = CompanySubscription::
            whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->where('payment_status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'USD');
            })
            ->count();

        $aff_naira = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->where('affiliates.status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'NGN');
            })
            ->count();
        $aff_dollar = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->where('affiliates.status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'USD');
            })
            ->count();

        $promo_naira = DB::table('coupon_transaction')
            ->join('coupons', 'coupon_transaction.coupon_id', '=', 'coupons.id')
            ->whereMonth('coupon_transaction.created_at', $currentMonth)
            ->whereYear('coupon_transaction.created_at', $currentYear)
            ->where('coupons.currency', 'NGN')
            ->sum('coupon_transaction.amount');
        
        $promo_dollar = DB::table('coupon_transaction')
            ->join('coupons', 'coupon_transaction.coupon_id', '=', 'coupons.id')
            ->whereMonth('coupon_transaction.created_at', $currentMonth)
            ->whereYear('coupon_transaction.created_at', $currentYear)
            ->where('coupons.currency', 'USD')
            ->sum('coupon_transaction.amount');
        // $tot_reffered = Affiliate::
        //     whereMonth('created_at', $currentMonth)
        //     ->whereYear('created_at', $currentYear)
        //     ->where('status', 'paid')
        //     ->count();
      
       $data = [
            'revenue_naira' => $rev_naira * env('AFFILATE_AMOUNT'),
            'revenue_dollar' => $rev_dollar * env('AFFILATE_AMOUNT_DOLLAR'),
            'affiliate_payout_naira' => $aff_naira * env('AFFILATE_AMOUNT'),
            'affiliate_payout_dollar' => $aff_dollar * env('AFFILATE_AMOUNT_DOLLAR'),
            'promo_redeemed_naira' => $promo_naira,
            'promo_redeemed_dollar' => $promo_dollar,

       ];

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

    public function getMonthAffiliateStats(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
      
        $aff_paid_dollar = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->where('affiliates.status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'USD');
            })
            ->count();
        
        $aff_paid_naira = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->where('affiliates.status', 'paid')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'NGN');
            })
            ->count();

        $aff_dollar = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'USD');
            })
            ->count();
        
        $aff_naira = CompanySubscription::
            whereMonth('company_subscription.payment_date', $currentMonth)
            ->whereYear('company_subscription.payment_date', $currentYear)
            ->where('company_subscription.payment_status', 'paid')
            ->join('affiliates', 'affiliates.user_id', '=', 'company_subscription.user_id')
            ->whereHas('subscription', function($q){
                $q->where('currency', 'NGN');
            })
            ->count();
    
        $tot_reffered = Affiliate::
            whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'paid')
            ->count();
      
       $data = [
            'total_affiliate_naira' => $aff_naira,
            'total_affiliate_dollar' => $aff_dollar,
            'total_affiliate_paid_naira' => $aff_paid_naira,
            'affiliate_payout_paid_dollar' => $aff_paid_dollar,
            'total_reffered' =>  $tot_reffered,

       ];

        return $this->successResponse($data, 'Operation successful');
    }


}
