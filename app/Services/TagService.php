<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\TagCategory;
use App\Trait\HandleResponse;
use App\Http\Resources\TagResource;

class TagService
{
    use HandleResponse;

    public function allTags()
    {
        $services = Tag::latest()->get();

        return $this->successResponse(TagResource::collection($services), 'All services');
    }

    public function createTag($request)
    {
        $category = TagCategory::create([
            'name' => $request->category,
        ]);

        Tag::create([
            'category_id' => $category->id,
            'name' => $request->service_name,
            'length' => $request->length,
            'width' => $request->width
        ]);

        return $this->successResponse([], 'Service added successfully', 201);
    }

    public function viewTag($id)
    {
        $service = Tag::find($id);
        if (! $service) {
            return $this->errorResponse(false, 'service not found', 404);
        }

        $data = new TagResource($service);

        return $this->successResponse($data, 'Service details');
    }

    public function editTag($request, $id)
    {
        $tag = Tag::find($id);

        if (! $tag) {
            return $this->errorResponse(false, 'service not found', 404);
        }

        $tag->update([
            'category_id' => $request->category_id,
            'name' => $request->service_name,
            'length' => $request->length,
            'width' => $request->width
        ]);

        return $this->successResponse(null, 'Details updated successfully');
    }

    public function deleteTag($id)
    {
        $tag = Tag::find($id);

        if (! $tag) {
            return $this->errorResponse(false, 'service not found', 404);
        }

        $tag->delete();

        return $this->successResponse(null, 'Service deleted successfully.');
    }

    public function allTagCategories()
    {
        $categories = TagCategory::select('id', 'name')->with('tags')->latest()->get();

        return $this->successResponse($categories, 'All Categories');
    }

    public function viewCategory($id)
    {
        $category = TagCategory::select('id', 'name')->find($id);
        if (! $category) {
            return $this->errorResponse(false, 'Category not found', 404);
        }

        return $this->successResponse($category, 'Category details');
    }

    public function editCategory($request, $id)
    {
        $category = TagCategory::select('id', 'name')->find($id);

        if (! $category) {
            return $this->errorResponse(false, 'Category not found', 404);
        }

        $category->update([
            'name' => $request->category,
        ]);

        return $this->successResponse(null, 'Details updated successfully');
    }

    public function deleteCategory($id)
    {
         $category = TagCategory::select('id', 'name')->find($id);

        if (! $category) {
            return $this->errorResponse(false, 'Category not found', 404);
        }

        if ($category->tags()->exists()) {
            return $this->errorResponse(false, 'Category cannot be deleted because it has active services', 400);
        }

        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully.');
    }
}
