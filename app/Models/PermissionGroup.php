<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PermissionGroup extends Model
{
    use HasFactory;

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
