<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDocuments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function requests(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

}
