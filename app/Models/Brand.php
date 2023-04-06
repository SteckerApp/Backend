<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'company_id',
        'subscription_id',
        'status',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];


    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    public function requests(): BelongsTo
    {
        return $this->belongsTo(ProjectRequest::class);
    }

    public function brandDocuments(): HasMany
    {
        return $this->hasMany(BrandDocuments::class);
    }

}
