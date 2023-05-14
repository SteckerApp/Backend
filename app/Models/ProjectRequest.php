<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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
    protected $casts = [
        // 'dimension' => 'array',
        'colors' => 'array',
        'deliverables' => 'array',
        'example_links' => 'array',
        // 'example_uploads' => 'json',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }
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
        return $this->hasMany(ProjectDeliverable::class, 'project_id');
    }
    public function projectMessage(): HasMany
    {
        return $this->hasMany(ProjectMessage::class, 'project_id');
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

    public function designer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'designer_id');
    }

    public function created_by(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function projectUser()
    {
        return $this->belongsToMany(User::class, 'project_user','project_id', 'user_id');
    }

    public function getDimensionAttribute($value)
    {
        return json_decode($value);
    }

    public function getColorsAttribute($value)
    {
        return json_decode($value);
    }

    public function getDeliverablesAttribute($value)
    {
        return json_decode($value);
    }

    public function getExampleLinksAttribute($value)
    {
        return json_decode($value);
    }

    public function getExampleUploadsAttribute($value)
    {
        return json_decode($value);
    }

    

}
