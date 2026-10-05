<?php

namespace App\Http\Controllers;

use App\Models\UserComment;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;


class UserCommentsController extends Controller
{
    use HandleResponse;

    public function store(Request $request,)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        $user = $request->user();
        $comment = UserComment::create([
            'comment' => $request->comment,
            'user_id' => $user->id,
        ]);

        return $this->successResponse($comment, 'Comment Created Succesfully', 201);
    }

    public function update(Request $request, $id)
    {

        $comment = UserComment::where('user_id', $request->user()->id)->whereId($id)->firstOrFail();
  
     if(!$comment){
        return $this->errorResponse(null, 'Comment not found', 404);
    }

    if($comment->status == 'approved'){
        return $this->errorResponse(null, 'Approved comments cannot be updated', 403);
    }
       $comment->update([
        'comment' => $request->comment
       ]);

        return $this->successResponse($comment, 'Comment updated successfully', 200);
    }
}
