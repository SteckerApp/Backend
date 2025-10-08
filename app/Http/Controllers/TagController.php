<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagCategory;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function __construct(
        protected TagService $tagService
    ) {}

    public function allTags()
    {
        $services = Tag::latest()->get();

        return $this->successResponse(TagResource::collection($services), 'All services');
    }

    public function createTag(TagRequest $request)
    {
        return $this->tagService->createTag($request);
    }

    public function viewTag($id)
    {
        return $this->tagService->viewTag($id);
    }

    public function editTag($request, $id)
    {
        return $this->tagService->editTag($request, $id);
    }

    public function deleteTag($id)
    {
        return $this->tagService->deleteTag($id);
    }

    public function allTagCategories()
    {
        return $this->tagService->allTagCategories();
    }

    public function viewCategory($id)
    {
        return $this->tagService->viewCategory($id);
    }

    public function editCategory($request, $id)
    {
        return $this->tagService->editCategory($request, $id);
    }

    public function deleteCategory($id)
    {
        return $this->tagService->deleteCategory($id);
    }
}
