<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandDocumentsRequest;
use App\Http\Requests\UpdateBrandDocumentsRequest;
use App\Models\BrandDocuments;

class BrandDocumentsController extends Controller
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
     * @param  \App\Http\Requests\StoreBrandDocumentsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandDocumentsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BrandDocuments  $brandDocuments
     * @return \Illuminate\Http\Response
     */
    public function show(BrandDocuments $brandDocuments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BrandDocuments  $brandDocuments
     * @return \Illuminate\Http\Response
     */
    public function edit(BrandDocuments $brandDocuments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrandDocumentsRequest  $request
     * @param  \App\Models\BrandDocuments  $brandDocuments
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandDocumentsRequest $request, BrandDocuments $brandDocuments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BrandDocuments  $brandDocuments
     * @return \Illuminate\Http\Response
     */
    public function destroy(BrandDocuments $brandDocuments)
    {
        //
    }
}
