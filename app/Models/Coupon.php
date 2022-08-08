<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'company_id',
        'user_id',
        'status',
        'updated_at'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
