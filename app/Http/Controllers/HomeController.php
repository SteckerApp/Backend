<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\UserComment;
use App\Services\HomeService;
use App\Trait\HandleResponse;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    use HandleResponse;

    public function migrate(): JsonResponse
    {
        Artisan::call('migrate', [
            '--force' => true
        ]);

        return response()->json([
            'message' => 'Migration completed successfully.',
            'output' => Artisan::output(),
        ]);
    }

    public function approvedComments(): JsonResponse
    {
       $comments = UserComment::with('user')
            ->join('admin_company', 'admin_company.user_id', '=', 'user_comments.user_id')
            ->where('status', 'approved')
            ->orderByDesc('user_comments.id')->get();

        return $this->successResponse($comments, 'Comments Fetched Succesfully', 200);
    }

}
