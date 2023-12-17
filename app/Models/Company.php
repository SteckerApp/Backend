<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'phone_number',
        'description',
        'hear_about_us',
        'avatar',
        'user_id',
        'account_manager',
    ];


    protected $hidden = [
        'user_id',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function activeSubscripitions()
    {
        return $this->belongsToMany(Subscription::class)->orderBy('payment_date', 'desc')->where(['status' => 'active' , 'payment_status' => 'paid']);
    }

    public function activeDefaultSubscripition()
    {
        return $this->belongsToMany(Subscription::class, 'company_subscription')->orderBy('payment_date', 'desc')->where(['company_subscription.status' => 'active' , 'company_subscription.payment_status' => 'paid', 'company_subscription.default' => 'yes']);
    }

    public function Subscripitions()
    {
        return $this->belongsToMany(Subscription::class)->orderBy('payment_date', 'desc');
    }

    /**
     * Get all of the allCompanyRequest for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function allCompanyRequest(): HasManyThrough
    {
        return $this->hasManyThrough(ProjectRequest::class, Brand::class);
    }

    public function pm()
    {
        return $this->hasOne(AdminCompany::class,'company_id')->where('role', 'PM')->with('user');
    }

    public function designer()
    {
        return $this->hasOne(AdminCompany::class,'company_id')->where('role', 'DESIGNER')->with('user');
    }

    public function owner()
    {
        return $this->belongsToMany(User::class)->where("role", "admin");
    }

    public function project_manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager');
    }


    public function getCardAuthorizationAttribute($value)
    {
        return json_decode($value);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(ProjectRequest::class);
    }


  
}
