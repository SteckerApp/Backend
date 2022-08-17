<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectRequest extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'brand_id',
        'user_id',
        'subscription_id',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(ProjectDeliverables::class, 'project_id');
    }

}
