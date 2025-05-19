<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
