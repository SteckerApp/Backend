<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;

    protected $table = 'company_user';

    protected $fillable = [
        'role',
        'user_id',
        "company_id"
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}