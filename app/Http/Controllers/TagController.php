<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagCategory;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Http\Requests\TagCategoryRequest;

class TagController extends Controller
{
    public function __construct(
        protected TagService $tagService
    ) {}

    public function allTags()
    {
        return $this->tagService->allTags();
    }

    public function createTag(TagRequest $request)
    {
        return $this->tagService->createTag($request);
    }

    public function viewTag($id)
    {
        return $this->tagService->viewTag($id);
    }

    public function editTag(Request $request, $id)
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

    public function createTagCategory(TagCategoryRequest $request)
    {
        return $this->tagService->createTagCategory($request);
    }
    public function viewCategory($id)
    {
        return $this->tagService->viewCategory($id);
    }

    public function editCategory(Request $request, $id)
    {
        return $this->tagService->editCategory($request, $id);
    }

    public function deleteCategory($id)
    {
        return $this->tagService->deleteCategory($id);
    }
}
