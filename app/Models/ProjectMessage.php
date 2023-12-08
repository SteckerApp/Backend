<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projectRequest()
    {
        return $this->belongsTo(ProjectRequest::class, 'project_id');
    }

    public function reply()
    {
        return $this->hasMany(ProjectMessage::class, 'reply_id')->with('reply.user');
    }


}
