<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'sub_facility_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the facility that this booking belongs to
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the sub-facility that this booking belongs to (if applicable)
     */
    public function subFacility(): BelongsTo
    {
        return $this->belongsTo(SubFacility::class, 'sub_facility_id');
    }

    /**
     * Get the user (teacher) who made this booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
