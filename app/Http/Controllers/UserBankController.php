<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBankRequest;
use App\Http\Requests\UpdateUserBankRequest;
use App\Models\UserBank;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Trait\HandleResponse;



class UserBankController extends Controller
{
    use HandleResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserBankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserBankRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserBank  $userBank
     * @return \Illuminate\Http\Response
     */
    public function show(UserBank $userBank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserBank  $userBank
     * @return \Illuminate\Http\Response
     */
    public function edit(UserBank $userBank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserBankRequest  $request
     * @param  \App\Models\UserBank  $userBank
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserBankRequest $request, UserBank $userBank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserBank  $userBank
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserBank $userBank)
    {
        //
    }

    public function getBankList()
    {
        
        $token = env('PAYSTACK_SECRET_KEY');
        $url = 'https://api.paystack.co/bank';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])
        // ->with([
        //     'param1' => 'value1',
        //     'param2' => 'value2',
        // ])
        ->get($url);

        return $this->successResponse(
            json_decode($response->body())
        );
    }

    public function verifyAccountNumber(Request $request)
    {
        
        $token = env('PAYSTACK_SECRET_KEY');
        $url = 'https://api.paystack.co/bank/resolve?account_number='.$request->account_number.'&bank_code='.$request->bank_code;


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])
        ->get($url);

        if(json_decode($response->body())->status){
            return $this->successResponse(
                json_decode($response->body())
            );
        }

        return $this->errorResponse(
            json_decode($response->body())
        );

       
    }
}
