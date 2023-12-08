<?php

namespace App\Http\Resources;

use App\Models\ProjectDeliverable;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public $author_id;

    public function __construct($resource, $author_id = null)
    {
        parent::__construct($resource);
        $this->author_id = $author_id;
    }

    public function toArray($request)
    {
        // return parent::toArray($request);
        $authorr_id = User::whereHas('author', function($q) {
            $q->whereId($this->project_id);
        })->first()->id;
        return [
            'message' => $this->message,
            'author' => ($authorr_id == $this->user_id)? true : false,
            'files' => ProjectDeliverable::where('project_message_id',$this->id )->get()->pluck('location')->toArray(),
            'user' => $this->user,
            'reply' => ($this->reply) ? ChatReplyResource::collection($this->reply) : [],
            'reply_count' => $this->reply_count

        ];
    }
}
