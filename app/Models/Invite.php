<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'invite_by',
        'role',
        'preset',
        'company_id',
        'platform',
        'status'
    ];

    protected $casts = [
        'preset' => 'array'
    ];

}
