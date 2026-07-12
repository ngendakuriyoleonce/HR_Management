<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'employee_id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'department_id',
    'position',
    'salary',
    'hire_date',
    'date_of_birth',
    'address',
    'city',
    'country',
    'status',
    'avatar',
])]
class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'inactive' => 'danger',
            'on_leave' => 'warning',
            default => 'gray',
        };
    }
}
