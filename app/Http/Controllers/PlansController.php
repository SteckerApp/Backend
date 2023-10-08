<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlansResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;

class PlansController extends Controller
{
    use HandleResponse;
    public function index(Request $request,)
    {
        $subscriptions = Subscription::query()
        ->when($request->input('type') == "plan", function ($query) use ($request) {
            $query->where('default', true);
        })
        ->when($request->input('type') == "addon", function ($query) use ($request) {
            $query->where('default', false);
        })
        ->when($request->input('currency'), function ($query) use ($request) {
            $query->where('currency', $request->currency);
        })
        ->when($request->input('billing_type'), function ($query) use ($request) {
            $query->whereIn('type', $request->input('billing_type'));
        })
        ->whereNotIn('id', [1])->get();

        $subscriptions= PlansResource::collection($subscriptions);

      
        return $this->successResponse($subscriptions, 'Plans retrive successfully!', 200);
    }

    public function setVisible(Request $request)
    {

        $this->validate($request, [
            'subscription_id' => 'required',
            'status' => 'required|string',
        ]);

        $subscription = Subscription::find($request->subscription_id);

        $subscription->visible = $request->status;

        $subscription->save();

        return $this->successResponse($subscription, 'Subscription updated successfully', 200);
    }

    public function setMostPopular(Request $request)
    {

        $this->validate($request, [
            'subscription_id' => 'required',
            'status' => 'required|string'
        ]);

        $subscription = Subscription::find($request->subscription_id);

        $subscription->most_popular = $request->status;

        $subscription->save();

        return $this->successResponse($subscription, 'Subscription updated successfully', 200);
    }

    public function createPlan(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'features' => 'required|array',
            'price_naira_monthly' => 'required',
            'slash_price_naira_monthly' => 'required',
            'discount_naira_monthly' => 'required',
            'price_dollar_monthly' => 'required',
            'slash_price_dollar_monthly' => 'required',
            'discount_dollar_monthly' => 'required',
            'price_naira_quarterly' => 'required',
            'slash_price_naira_quarterly' => 'required',
            'discount_naira_quarterly' => 'required',
            'price_dollar_quarterly' => 'required',
            'slash_price_dollar_quarterly' => 'required',
            'discount_dollar_quarterly' => 'required',
            'price_naira_bi_annually' => 'required',
            'slash_price_naira_bi_annually' => 'required',
            'discount_naira_bi_annually' => 'required',
            'price_dollar_bi_annually' => 'required',
            'slash_price_dollar_bi_annually' => 'required',
            'discount_dollar_bi_annually' => 'required',
            'price_naira_yearly' => 'required',
            'slash_price_naira_yearly' => 'required',
            'discount_naira_yearly' => 'required',
            'price_dollar_yearly' => 'required',
            'slash_price_dollar_yearly' => 'required',
            'discount_dollar_yearly' => 'required',
        ]);

        $identifier = uniqid('SUB');

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_naira_monthly,
            'type' => "monthly",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_naira_monthly,
            'currency' => "NGN",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_dollar_monthly,
            'type' => "monthly",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_dollar_monthly,
            'currency' => "USD",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_naira_quarterly,
            'type' => "quarterly",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_naira_quarterly,
            'currency' => "NGN",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_dollar_quarterly,
            'type' => "quarterly",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_dollar_quarterly,
            'currency' => "USD",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_naira_bi_annually,
            'type' => "bi-annually",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_naira_bi_annually,
            'currency' => "NGN",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_dollar_bi_annually,
            'type' => "bi-annually",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_dollar_bi_annually,
            'currency' => "USD",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_naira_yearly,
            'type' => "annually",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_naira_yearly,
            'currency' => "NGN",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'metadata' => $request->features,
            'price' => $request->price_dollar_yearly,
            'type' => "annually",
            'default' => true,
            'info' => true,
            'discounted' => $request->slash_price_dollar_yearly,
            'currency' => "USD",
            'group_identifier' =>  $identifier,

        ]);

        return $this->successResponse($subscription, 'Subscription created successfully', 200);
    }

    public function createAddon(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price_naira_monthly' => 'required',
            'price_dollar_monthly' => 'required',
        ]);

        $identifier = uniqid('SUB');

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price_naira_monthly,
            'type' => "monthly",
            'default' => false,
            'info' => false,
            'currency' => "NGN",
            'group_identifier' =>  $identifier,

        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price_naira_monthly,
            'type' => "monthly",
            'default' => false,
            'info' => false,
            'group_identifier' =>  $identifier,
            'currency' => "USD"
        ]);

        return $this->successResponse($subscription, 'Subscription created successfully', 200);
    }




}
