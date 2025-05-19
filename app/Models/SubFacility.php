<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'capacity',
        'status',
        'image_path',
        'is_bookable',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
    ];

    /**
     * Get the facility that owns the sub-facility.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
