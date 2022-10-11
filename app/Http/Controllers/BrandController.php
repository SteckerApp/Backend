<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\BrandDocuments;
use Illuminate\Support\Facades\Session;


class BrandController extends Controller
{
    use HandleResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
        // $this->authorizeResource(Brand::class, 'brand' );
     }

    public function index(Request $request,)
    {
        $perPage = ($request->perPage) ?? 20;
        $brands = Brand::with('brandDocuments')->whereCompanyId(
            getActiveWorkSpace()->id
        );

        $brands = $brands->paginate($perPage);

        return $this->successResponse($brands, 'Brand fetch successfully', 200);
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
            'industry' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'audience' => 'sometimes|string',
            'website' => 'sometimes|string|max:100',
            'colors' => "sometimes|array"
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'company_id' => getActiveWorkSpace()->id,
            'description' => $request->description,
            'website' => $request->website,
            'industry' => $request->industry,
            'colors' => $request->colors
        ]);

        //Upload Brand Documents
        if($request->hasfile('attachments'))
         {
            foreach($request->file('attachments') as $key => $file)
            {
                $key++;
                $path = "/companies/brands/".$brand->id."/documents";
                $name = $file->getClientOriginalName();
                $doc_link = uploadDocument($file, $path, $name);

                $record = BrandDocuments::create([
                    'brand_id' => $brand->id,
                    'upload' => $doc_link,
                    'user_id' => auth()->user()->id,
                ]);
            }
         }

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
        $brand = Brand::with('brandDocuments')->whereIdAndCompanyId($id, getActiveWorkSpace()->id)->firstOrFail();

        return $this->successResponse($brand, 'Brand fetch successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $brand = Brand::whereIdAndCompanyId($id, getActiveWorkSpace()->id)
            ->firstOrFail();

        tap($brand, function ($collection) use ($brand, $request) {
            $brand->fill(
                $request->only([
                    'name',
                    'company_id',
                    'subscription_id',
                    'description',
                    'industry',
                    'guideline'
                ])
            );
            return $collection->save();
        });

        return $this->successResponse($brand, 'Brand updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $brand = Brand::whereIdAndCompanyId($id, getActiveWorkSpace()->id)
            ->firstOrFail();

        $brand->delete();

        return $this->successResponse($brand, 'Brand deleted successfully', 200);
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
