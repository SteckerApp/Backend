<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
       
    ];

    public function companySubscription(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_subscription');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_subscription');
    }

}
