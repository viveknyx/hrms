<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'description', 'manager_user_id', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function designations(): HasMany
    {
        return $this->hasMany(Designation::class);
    }
}
