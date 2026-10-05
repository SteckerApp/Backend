<?php

namespace App\Http\Controllers;

use App\Models\UserComment;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;

class AdminCommentManagerController extends Controller
{
    use HandleResponse;

    public function index(Request $request,)
    {
        $comments = UserComment::with('user')
            ->join('admin_company', 'admin_company.user_id', '=', 'user_comments.user_id')
            ->where('status', 'approved')
            ->orderByDesc('user_comments.id')->get();

        return $this->successResponse($comments, 'Comments Fetched Succesfully', 200);
    }


    public function approveComment(Request $request, $id)
    {
        $comment = UserComment::whereId($id)->firstOrFail();

        if (!$comment) {
            return $this->errorResponse(null, 'Comment not found', 404);
        }

        if ($comment->status == 'approved') {
            return $this->errorResponse(null, 'Comment is already approved', 403);
        }

        $comment = UserComment::whereId($id)->update([
            'status' => 'approved'
        ]);

        return $this->successResponse($comment, 'Comment approved successfully', 200);
    }

    public function commentDetails(Request $request, $id)
    {
        $comment = UserComment::with('user')->whereId($id)->firstOrFail();

        return $this->successResponse($comment, 'Comment retrieved successfully', 200);
    }

    public function destroy(Request $request, $id)
    {
        $comment = UserComment::whereId($id)->firstOrFail();

        $comment->delete();

        return $this->successResponse($comment, 'Comment deleted successfully', 200);
    }
}
