<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMessageController extends Controller
{
    public function fetchMessages($project_id)
    {
        $messages = Message::with('user')->where('project_id',$project_id)->get();

        return $this->successResponse($messages , '', 200);
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $message = $user->messages()->create([
            'message' => $request->message,
            'project_id' => $request->project_id,
        ]);

        return $this->successResponse(true , 'Message Sent', 200);
    }

}
