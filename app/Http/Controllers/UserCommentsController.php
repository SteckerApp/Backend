<?php

namespace App\Http\Controllers;

use App\Models\UserComment;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;


class UserCommentsController extends Controller
{
    use HandleResponse;

    public function index(Request $request,)
    {
        $comments= UserComment::with('user')
        ->join('admin_company', 'admin_company.user_id', '=', 'user_comments.user_id')
        ->where('status', 'approved')
        ->orderByDesc('user_comments.id')->get();

        return $this->successResponse($comments, 'Comments Fetched Succesfully', 200);
    }

    public function store(Request $request,)
    {
        $this->validate($request, [
            'comment' => 'required',
            'user_id' => 'required',
        ]);

        $comment = UserComment::create([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
        ]);

        return $this->successResponse($comment, 'Comment Created Succesfully', 201);
    }

    public function update(Request $request, $id)
    {

        $comment = UserComment::whereId($id)->firstOrFail();

        tap($comment, function ($collection) use ($comment, $request) {
            $comment->fill(
                $request->only([
                    'comment',
                ])
            );
            return $collection->save();
        });

        return $this->successResponse($comment, 'Comment updated successfully', 200);
    }
    public function approveComment(Request $request, $id)
    {

        $comment = UserComment::whereId($id)->update([
            'status' => 'approved'
        ]);

        return $this->successResponse($comment, 'Comment approved successfully', 200);
    }

    public function destroy(Request $request, $id)
    {
        $comment = UserComment::whereId($id)->firstOrFail();

        $comment->delete();

        return $this->successResponse($comment, 'Comment deleted successfully', 200);
    }
}
