<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request,)
    {
        $perPage = ($request->perPage) ?? 10;
        $projects = Portfolio::query();

        ($request->brochuresandflyers) ? $projects =  $projects->where('category', 'brochures&flyers') :"";
        ($request->book_graphics) ? $projects =  $projects->where('category', 'book_graphics'):"";
        ($request->custom_illustrations) ? $projects =  $projects->where('category', 'custom_illustrations'):"";
        ($request->GIFs) ? $projects =  $projects->where('category', 'GIFs'):"";
        ($request->info_graphics) ? $projects =  $projects->where('category', 'info_graphics'):"";
        ($request->landing_pages) ? $projects =  $projects->where('category', 'landing_pages'):"";

        ($request->page) ? $projects =  $projects->paginate($perPage) : $projects = $projects->get();

        return $this->successResponse($projects, 'Portfolio Fetched Succesfully', 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);
        $file = $request->attachment;
        $path = "/portfolio".$request->category."/";
        $name = $request->attachment->getClientOriginalName();
        $doc_link = uploadDocument($file, $path, $name);

        $brand = Portfolio::create([
            'category' => $request->category,
            'location' => $doc_link,
        ]);

        return $this->successResponse($brand, 'Portfolio created successfully', 201);
    }

    public function destroy(Request $request, $id)
    {
        $brand = Portfolio::whereId($id)->firstOrFail();
        $brand->delete();

        return $this->successResponse($brand, 'Brand deleted successfully', 200);
    }
}
