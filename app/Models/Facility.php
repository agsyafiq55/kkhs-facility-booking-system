<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'status',
        'image_path',
        'has_addons',
        'has_sub_facilities',
    ];
    
    protected $casts = [
        'has_addons' => 'boolean',
        'has_sub_facilities' => 'boolean',
    ];

    /**
     * Get the addons for the facility.
     */
    public function addons(): HasMany
    {
        return $this->hasMany(FacilityAddon::class);
    }

    /**
     * Get the sub-facilities for the facility.
     */
    public function subFacilities(): HasMany
    {
        return $this->hasMany(SubFacility::class);
    }
}
