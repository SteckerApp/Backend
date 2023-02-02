<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'subscription_id', 'default', 'duration', 'unit', 'total'
    ];

    /**
     * Get the subscription associated with the Transaction
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
