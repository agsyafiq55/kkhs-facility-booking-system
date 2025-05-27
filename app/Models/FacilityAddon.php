<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FacilityAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'is_available',
        'quantity_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the facility that owns the addon.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
    
    /**
     * Get the bookings that use this add-on.
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_facility_addon')
                    ->withPivot('quantity', 'notes')
                    ->withTimestamps();
    }
}
