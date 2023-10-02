<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;


class PortfolioController extends Controller
{
    use HandleResponse;

    public function index(Request $request,)
    {
        $projects= Portfolio::
        when($request->input('category_id'), function ($query) use ($request) {
            $query->where('portfolio_category_id', $request->input('category_id'));
        })
       -> when($request->input('limit'), function ($query) use ($request) {
            $query->limit(9);
        })
        ->get();

        return $this->successResponse($projects, 'Portfolio Fetched Succesfully', 200);
    }

    public function storeImage(Request $request)
    {
        $this->validate($request, [
            'portfolio_category_id' => 'required',
            'attachments' => 'required|array',
        ]);

        if ($request->hasfile('attachments')) {
            foreach ($request->file('attachments') as $key => $file) {
                $key++;
                $path = "/portfolio/".$request->portfolio_category_id;
                $name = $file->getClientOriginalName();
                $doc_link = uploadDocument($file, $path, $name);

                $brand = Portfolio::create([
                    'portfolio_category_id' => $request->portfolio_category_id,
                    'location' => $doc_link,
                ]);
            }
        }

        return $this->successResponse($brand, 'Portfolio created successfully', 201);
    }

    public function storeVideos(Request $request)
    {
        $this->validate($request, [
            'portfolio_category_id' => 'required',
            'videos' => 'required|array',
        ]);

        foreach ($request->input('videos') as $video) {

            $link = $video['link'];
	        $thumbnail = $video['thumbnail'];

            $path = "/portfolio/thumbnail/".$request->portfolio_category_id;;
            $name = $video['thumbnail']->getClientOriginalName();
            $doc_link = uploadDocument($video['thumbnail'], $path, $name);

            $record = Portfolio::create([
                'portfolio_category_id' => $request->portfolio_category_id,
                'location' => $link,
                'thumbnail' => $doc_link,
                'type' => 'video',
            ]);
        }

        return $this->successResponse($record, 'Portfolio videos created successfully', 201);
    }


    public function destroy(Request $request, $id)
    {
        $brand = Portfolio::whereId($id)->firstOrFail();
        $brand->delete();

        return $this->successResponse($brand, 'Brand deleted successfully', 200);
    }
}
