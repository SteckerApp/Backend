<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;


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
        $subscriptions = Subscription::all()->groupBy('type');

        $data = [];
        foreach ( $subscriptions as $subscription){
         $sub = (object)[];
         $sub->name = $subscription[0]->type;
         $sub->plans = $subscription;
         array_push($data,$sub);
        }

        return $this->successResponse($data, '', 200);

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
        return $this->successResponse(
            [
                'subscription' => $company->activeSubscripitions()->where('default', 'yes')->first(),
                'history' => $company->activeSubscripitions()->limit(5)->get()
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
}
