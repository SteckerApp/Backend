<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;


class BrandController extends Controller
{
    use HandleResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,)
    {
        $perPage = ($request->perPage) ?? 10;

        $brands = Brand::with('company')->whereHas('company', function ($q) {
            $q->where('user_id', auth()->user()->id);
        });

        ($request->page) ? $brands =  $brands->paginate($perPage) : $brands =  $brands->get();

        return $this->successResponse($brands, '', 200);
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
            'name' => 'required',
            'company_id' => 'required',
            'subscription_id' => 'required',
            'guideline' => 'mimes:jpg,jpeg,png,svg,pdf,eps,gif,adobe|max:5000',
        ]);

       if($request->guideline){
        $path = "/".auth()->user()->id."/brands/".$request->name."/guidelines/";
        $name = $request->guideline->getClientOriginalName();
        $doc_link = uploadDocument($request->guideline, $path, $name);
       }
        $brand = Brand::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'subscription_id' => $request->subscription_id,
            'description' => $request->description,
            'website' => $request->website,
            'industry' => $request->industry,
            'guideline' => $request->guideline ? $doc_link : null,
        ]);

        return $this->successResponse($brand, 'Brand created successfully', 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $brand = Brand::where('id',$id)->first();

        if($brand){
            return $this->successResponse($brand, '', 200);
        }

        return $this->errorResponse($brand);

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
            'name' => 'required',
            'company_id' => 'required',
            'subscription_id' => 'required',
            'guideline' => 'mimes:jpg,jpeg,png,svg,pdf,eps,gif,adobe|max:5000',
        ]);

        $brand = Brand::where('id',$id);
        $updated = $brand->update(
            [
                'name' => $request->name,
                'company_id' => $request->company_id,
                'subscription_id' => $request->subscription_id,
                'description' => $request->description,
                'website' => $request->website,
                'industry' => $request->industry,
                'guideline' => $request->guidelines,
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
        $brand = Brand::find($id);
        if($brand){
            $deleted = $brand->delete();
            return $this->successResponse($deleted, 'Brand deleted successfully', 200);
        }

        return $this->errorResponse($brand);
    }

    public function restore($id)
    {
        $restored = Brand::withTrashed()->find($id)->restore();

        return $this->successResponse($restored, 'Brand restored successfully', 200);
    }

    public function restoreAll()
    {
        $restored = Brand::with('company')->whereHas('company', function ($q) {
            $q->where('user_id', auth()->user()->id);
        });

        $restored ? $restored->onlyTrashed()->restore() : null;

        return $this->successResponse($restored, 'Brands restored successfully', 200);
    }
}
