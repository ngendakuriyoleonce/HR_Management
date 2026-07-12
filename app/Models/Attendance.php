<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['employee_id', 'date', 'clock_in', 'clock_out', 'status', 'notes'])]
class Attendance extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'half_day' => 'info',
            default => 'gray',
        };
    }

    public function getHoursWorkedAttribute(): ?float
    {
        if ($this->clock_in && $this->clock_out) {
            return round($this->clock_in->diffInMinutes($this->clock_out) / 60, 2);
        }

        return null;
    }
}
