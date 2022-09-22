<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone_number',
        'user_type',
        'currency',
        'referral_code',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get all of the companies for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownCompanies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * The test that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }
    
    public function allAttachedCompany(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    public function companySubscription(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function coupon(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Affiliate::class, 'referral_id');
    }

    public function bank()
    {
        return $this->hasOne(UserBank::class);
    }

    public function brands()
    {
        return $this->hasManyThrough(Brand::class, Company::class);
    }

    public function messages()
    {
        return $this->hasMany(ProjectMessage::class);
    }
}
