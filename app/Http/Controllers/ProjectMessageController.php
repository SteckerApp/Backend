<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectMessage;
use Illuminate\Support\Facades\Auth;


class ProjectMessageController extends Controller
{
    use HandleResponse;
    public function fetchMessages($project_id)
    {
        $messages = ProjectMessage::with('user.roles')->where('project_id',$project_id)->get();

        return $this->successResponse($messages , '', 200);
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $message = $user->messages()->create([
            'message' => $request->message,
            'project_id' => $request->project_id,
        ]);

        // broadcast(new MessageSent($user, $message))->toOthers();
        event(new MessageSent($user, $message));

        return $this->successResponse(true , 'Message Sent', 200);
    }

}
