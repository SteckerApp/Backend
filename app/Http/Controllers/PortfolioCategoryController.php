<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioCategory;

class PortfolioCategoryController extends Controller
{
    public function store(Request $request,)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $categ = PortfolioCategory::create([
            'category' => $request->name,
        ]);



        return $this->successResponse($categ, 'Portfolio Created Succesfully', 201);
    }

    public function update(Request $request, $id)
    {

        $categ = PortfolioCategory::whereId($id)->firstOrFail();

        tap($categ, function ($collection) use ($categ, $request) {
            $categ->fill(
                $request->only([
                    'name',
                ])
            );
            return $collection->save();
        });

        return $this->successResponse($categ, 'Category updated successfully', 200);
    }

    public function destroy(Request $request, $id)
    {
        $categ = PortfolioCategory::whereId($id)->firstOrFail();

        $categ->delete();

        return $this->successResponse($categ, 'Category deleted successfully', 200);
    }


}
