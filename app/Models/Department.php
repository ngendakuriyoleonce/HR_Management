<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'manager_id'])]
class Department extends Model
{
    use HasFactory;

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function manager(): HasOne
    {
        return $this->hasOne(Employee::class, 'id', 'manager_id');
    }
}
