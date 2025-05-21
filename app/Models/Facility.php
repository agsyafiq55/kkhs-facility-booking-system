<?php

namespace App\Models;

use Carbon\Carbon;
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
        'opening_time',
        'closing_time',
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
    
    /**
     * Get the bookings for the facility.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    
    /**
     * Get the bookings for this facility as a sub-facility.
     */
    public function subFacilityBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'sub_facility_id');
    }
    
    /**
     * Generate available time slots for a given date
     * 
     * @param string $date Date in Y-m-d format
     * @return array Array of available time slots
     */
    public function getAvailableTimeSlots($date)
    {
        // Generate all possible time slots based on opening and closing times
        $openingTime = Carbon::parse($this->opening_time);
        $closingTime = Carbon::parse($this->closing_time);
        
        // Special handling for midnight (00:00) - treat it as 24:00
        if ($closingTime->format('H:i') === '00:00') {
            $closingTime = Carbon::parse('23:59:59');
        }
        
        $timeSlots = [];
        $currentSlot = clone $openingTime;
        
        while ($currentSlot->lt($closingTime)) {
            $slotEnd = (clone $currentSlot)->addHour();
            
            if ($slotEnd->gt($closingTime)) {
                $slotEnd = clone $closingTime;
            }
            
            $timeSlots[] = [
                'start' => $currentSlot->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'formatted' => $currentSlot->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'available' => true,
            ];
            
            $currentSlot->addHour();
        }
        
        // Check which slots are booked
        $bookings = $this->bookings()
            ->where('date', $date)
            ->where(function($query) {
                $query->where('sub_facility_id', null)
                      ->orWhere('sub_facility_id', 0);
            })
            ->get();
        
        foreach ($bookings as $booking) {
            $bookingStart = Carbon::parse($booking->start_time);
            $bookingEnd = Carbon::parse($booking->end_time);
            
            foreach ($timeSlots as &$slot) {
                $slotStart = Carbon::parse($slot['start']);
                $slotEnd = Carbon::parse($slot['end']);
                
                // Check if booking overlaps with this slot
                if (
                    ($bookingStart->lte($slotStart) && $bookingEnd->gt($slotStart)) ||
                    ($bookingStart->lt($slotEnd) && $bookingEnd->gte($slotEnd)) ||
                    ($bookingStart->gte($slotStart) && $bookingEnd->lte($slotEnd))
                ) {
                    $slot['available'] = false;
                }
            }
        }
        
        return $timeSlots;
    }
}
