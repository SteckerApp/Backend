<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\ProjectDeliverable;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $authorr_id = User::whereHas('author', function($q) {
            $q->whereId($this->project_id);
        })->first()->id;

        return [
            'message' => $this->message,
            'author' => ($authorr_id == $this->user_id) ? true : false,
            'files' => ProjectDeliverable::where('project_message_id',$this->id )->get()->pluck('location')->toArray(),
            'user' => $this->user,
            'reply' => ($this->reply) ? ChatReplyResource::collection($this->reply) : [],
            'reply_count' => count($this->reply)
        ];
    }
}
