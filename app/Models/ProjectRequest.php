<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProjectRequest extends Model
{
    use HasFactory, SoftDeletes;
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

    /**
     * Get the pm associated with the ProjectRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pm(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'pm_id');
    }

}
