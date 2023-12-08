<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Models\ProjectMessage;
use App\Models\ProjectRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectMessageResource;


class ProjectMessageController extends Controller
{
    use HandleResponse;
    protected $projectDeliverablesController;

    public function __construct(ProjectDeliverablesController $projectDeliverablesController)
    {
        $this->projectDeliverablesController = $projectDeliverablesController;
    }

    public function fetchMessages($project_id)
    {
        $messages = ProjectMessage::with(['user', 'reply.user'])->withCount('reply')->whereNull('reply_id')->where('project_id',$project_id)->get();
        $author_id = User::whereHas('author', function($q) use ($project_id){
            $q->whereId($project_id);
        })->first()->id;

        
        $data = ProjectMessageResource::collection($messages,$author_id);


        return $this->successResponse( $data , 'Messages fetched succesfully', 200);
    }

    public function sendMessage(Request $request)
    {
        $this->validate(
            $request,
            [
            'project_id' => 'required'
            ]
        );
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->message,
            'project_id' => $request->project_id,
        ]);

        if($request->hasfile('attachments')){
            $type='attachment';
            $request->merge([
                'project_message_id' => $message->id,
                'title' => ProjectRequest::whereId($request->project_id)->first()->title,
             ]);

            $response = $this->projectDeliverablesController->uploadDeliverables($request);
            if ($response->getStatusCode() !== 201) {
            // Handle the error, e.g., return a response or redirect
            return response()->json(['error' => 'Validation failed'], $response->getStatusCode());
            }

            $this->notify($request,$message,$request->project_id,$type);
        }

        if($request->reply){
            $type='comment';
            $this->notify($request,$message,$request->project_id,$type);
            
        }
        // broadcast(new MessageSent($user, $message))->toOthers();
        event(new MessageSent($message));

        return $this->successResponse(true , 'Message Sent', 200);
    }

    public function notify($request, $message, $project_id, $type)
    {
        $project_users = DB::table('project_user')->where('project_id', $project_id )->where('user_id', '!=', $request->user()->id)->get();

            foreach($project_users as $project_user){

                $data = [
                    'user_id' => $project_user->user_id,
                    'type' => $type ?? null,
                    'project_id' => $request->project_id,
                    'project_message_id' => $message->id,
                    'commenter_id' => ($request->reply)? $request->reply : null
                ];

                $notification = new NotificationController();
                $notification->store($data);
            }
    }


}
