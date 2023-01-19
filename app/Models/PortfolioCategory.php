<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioCategory extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function portfolio(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}
