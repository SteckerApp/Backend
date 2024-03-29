<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference','subtotal','total','status', 'user_id','main_subscription_id'
    ];

    public const STATUS = [
        'pending',
        'paid',
        'canceled'
    ];

    /**
     * Get all of the transactions for the Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'reference', 'reference');
    }

    public function subscription(): HasMany
    {
        return $this->hasMany(Subscription::class, 'id', 'main_subscription_id');
    }

    protected $casts = [
        'discounted' => 'array',
    ];
}
