<?php

namespace App\Http\Controllers;

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


        return $this->successResponse($subscriptions, '', 200);
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
        ]);

        $subscription = Subscription::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'metadata' => $request->metadata,
            'currency' => $request->currency,
            'order' => $request->order,
            'user_limit' =>  $request->user_limit,
            'design_limit' =>  $request->design_limit,
        ]);

        return $this->successResponse($subscription, 'Brand created successfully', 201);

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
        $subscription = Subscription::find($id);

        $updated = $subscription->update(
            [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'type' => $request->type,
                'metadata' => $request->metadata,
                'currency' => $request->currency,
                'order' => $request->order,
                'user_limit' =>  $request->user_limit,
                'design_limit' =>  $request->design_limit,
            ]
        );

        return $this->successResponse($updated, 'Brand updated successfully', 200);
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

        return $this->successResponse($deleted, 'Brand deleted successfully', 200);
    }

}
