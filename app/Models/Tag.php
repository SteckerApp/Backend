<?php

namespace App\Models;

use App\Models\TagCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'length',
        'width'
    ];

    public function tagCategory()
    {
        return $this->belongsTo(TagCategory::class, 'category_id');
    }
}
