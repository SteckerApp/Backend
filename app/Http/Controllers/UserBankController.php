<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBankRequest;
use App\Http\Requests\UpdateUserBankRequest;
use App\Models\UserBank;

class UserBankController extends Controller
{
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
}
