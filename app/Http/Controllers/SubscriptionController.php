<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class subscriptionController extends Controller
{
    use HandleResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,)
    {
        $subscriptions = Subscription::query();
        if($request->type == '0'){
            $subscriptions = $subscriptions->where('default', false)->whereNotIn('id', [1]);
        }
        else{
            $subscriptions = $subscriptions->where('default', true)->whereNotIn('id', [1]);
        }
        $subscriptions = $subscriptions->get()->groupBy('type');

        return $this->successResponse($subscriptions, 'Subscription retrive successfully!', 200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addons(Request $request,)
    {
        $subscriptions = Subscription::where('default', false)->get();

        return $this->successResponse($subscriptions, 'Subscription retrive successfully!', 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
            'type' => 'required|in:monthly,quarterly,yearly',
            'default' => 'sometimes|boolean',
            'info' => 'sometimes|boolean',
            'discounted' => 'sometimes|integer'
        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'metadata' => $request->metadata,
            'default' => $request->default,
            'info' => $request->info,
            'discounted' => $request->discounted,
            'currency' => $request->currency,
            'order' => $request->order,
            'user_limit' =>  $request->user_limit,
            'design_limit' =>  $request->design_limit,
        ]);

        return $this->successResponse($subscription, 'Subscription created successfully', 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $subscription = Subscription::find($id);
        return $this->successResponse($subscription->first(), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'price' => 'sometimes|integer',
            'type' => 'sometimes|in:monthly,quarterly,yearly',
            'default' => 'sometimes|boolean',
            'info' => 'sometimes|boolean',
            'discounted' => 'sometimes|integer'
        ]);

        $subscription = Subscription::find($id);

        $subscription->fill($request->only([
            'title',
            'description',
            'price',
            'type',
            'metadata',
            'default',
            'info',
            'discounted',
            'currency',
            'order',
            'user_limit',
            'design_limit',
        ]));

        $subscription->update();

        return $this->successResponse($subscription, 'Subscription updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        $deleted =  $subscription->delete();

        return $this->successResponse($subscription, 'Subscription deleted successfully', 200);
    }

    public function activeSub(Request $request)
    {
        $company = Company::whereId(getActiveWorkSpace()->id)->first();
        // dd($company->activeSubscripitions()->select(['subscriptions.*', 'company_subscription.end_date', 'company_subscription.type'])->where('subscriptions.default', true)->toSql());
        return $this->successResponse(
            [
                'subscription' =>$company->activeSubscripitions()
                ->select(['subscriptions.*', 'subscriptions.id as subscription_id','company_subscription.type', DB::raw("DATE_FORMAT(company_subscription.start_date, '%Y-%m-%dT%H:%i:%s.%fZ') as start_date"), DB::raw("DATE_FORMAT(company_subscription.end_date, '%Y-%m-%dT%H:%i:%s.%fZ') as end_date")])
                ->where('subscriptions.default', true)->first(),
                'card_authorization' =>$company->card_authorization,
                'auto_renewal' =>$company->auto_renew,
                'history' => $company->activeSubscripitions()
                ->select([
                    'subscriptions.title',
                    'subscriptions.type',
                    'subscriptions.discounted',
                    'subscriptions.price as unit_price',
                    'subscriptions.id as subscription_id',
                    'company_subscription.type',
                    'company_subscription.id as order_number',
                    'company_subscription.reference as purchase_order',
                    'company_subscription.payment_date as invoice_date',
                    'company_subscription.payment_date as invoice_date',
                    'transactions.unit as quantity',
                   DB::raw("DATE_FORMAT(company_subscription.start_date, '%Y-%m-%dT%H:%i:%s.%fZ') as start_date"), 
                   DB::raw("DATE_FORMAT(company_subscription.end_date, '%Y-%m-%dT%H:%i:%s.%fZ') as end_date")])
                ->join('transactions', 'transactions.reference', '=', 'company_subscription.reference')
                ->limit(5)
                ->get(),
                'addon' => $company->activeSubscripitions()
                    ->select(['subscriptions.*', 'subscriptions.id as subscription_id', 'company_subscription.type', DB::raw("DATE_FORMAT(company_subscription.start_date, '%Y-%m-%dT%H:%i:%s.%fZ') as start_date"), DB::raw("DATE_FORMAT(company_subscription.end_date, '%Y-%m-%dT%H:%i:%s.%fZ') as end_date")])
                    ->where('subscriptions.default', false)
                    ->get()
            ]
        );
    }

    public function list(Request $request)
    {
        $company = Company::whereId(getActiveWorkSpace()->id)->first();
        return $this->successResponse(
            $company->activeSubscripitions()->paginate(20)
        );
    }

    public function three_days_expiration_reminder(Request $request)
    {
        $subscription = CompanySubscription::where([
            'company_id' => getActiveWorkSpace()->id
        ])
        ->whereHas('subscription', function ($q) {
            $q->where('default', true);
        })
        ->whereDate('end_date', '>=', Carbon::now())->first();

        if($subscription){
            $end_date = $subscription->end_date;

            // Set the end date
            $end_date = Carbon::parse($end_date);
    
    
            // Calculate the date 3 days before the end date
            // $three_days_before_end_date = $end_date->sub(3, 'day');
    
            // Get the current date and time
            $current_time = now();
    
            // Calculate the difference between the end date and the current date
            $diff_in_days = $current_time->diffInDays($end_date);
    
            // Check if the remaining days are less than 3
            if ($diff_in_days < 3) {
                // Calculate the difference between the current date and the end date
                // $diff_in_days = $current_time->diffInDays($end_date, true);
    
                $data = [
                    'status' => 'expiring',
                    'days' => $diff_in_days
                ];
                
            } else {
                $data = [
                    'status' => 'active',
                ];
            }
        }

        else{
            $data = [
                'status' => 'expired',
            ];
        }

       
        return $this->successResponse(
            $data
        );
    }

    public function cancelSubscription(Request $request)
    {
        $subscription = CompanySubscription::where([
            'company_id' => getActiveWorkSpace()->id,
            'subscription_id' => $request->subscription_id,
            'payment_status' => "paid",
            'status' => "active"
        ])
        ->whereDate('end_date', '>=', Carbon::now())->firstOrFail();

        $subscription->payment_status = "cancelled";
        $subscription->save();

        return $this->successResponse(
            $subscription
        );
    }

    public function autoRenew(Request $request)
    {
        Company::whereId(getActiveWorkSpace()->id)->update(
            [
                "auto_renew"=> $request->auto_renew
            ]
        );
        return $this->successResponse(
            true
        );
    }

    public function removeCard(Request $request)
    {
        Company::whereId(getActiveWorkSpace()->id)->update(
            [
                "card_authorization"=> null
            ]
        );
        return $this->successResponse(
            true
        );
    }
}
