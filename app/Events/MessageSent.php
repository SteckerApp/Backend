<?php

namespace App\Events;

use App\Models\User;
use App\Models\ProjectMessage;
use App\Models\ProjectDeliverable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\ChatReplyResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use App\Http\Resources\ProjectMessageResource;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $project_message;

    public function __construct(ProjectMessage $project_message)
    {
        $this->project_message = $project_message;

        $this->project_message = ProjectMessage::with(['user', 'reply.user'])->withCount('reply')->whereId($this->project_message->id)->first();



    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('chat');
        return 'chat'.$this->project_message->project_id;

    }

    public function broadcastAs()
    {
        return 'message-sent';
    }

    public function broadcastWith()
    {
        $authorr_id = User::whereHas('author', function($q) {
            $q->whereId($this->project_message->project_id);
        })->first()->id;

        $this->project_message->files = ProjectDeliverable::where('project_message_id',$this->project_message->id )->get()->pluck('location')->toArray();
        $this->project_message->reply = ($this->project_message->reply) ? ChatReplyResource::collection($this->project_message->reply) : [];
        $this->project_message->author = ($authorr_id == $this->project_message->user_id)? true : false;
    
        return ['data' => $this->project_message];

    }
}
