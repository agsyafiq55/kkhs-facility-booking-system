<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\SubFacility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FacilityBooking extends Component
{
    public Facility $facility;
    public ?SubFacility $subFacility = null;
    public string $selectedDate;
    public array $selectedTimeSlots = [];
    public array $availableTimeSlots = [];
    public string $purpose = '';
    public ?string $debug = null;
    
    // Add-ons related properties
    public array $availableAddons = [];
    public array $selectedAddons = [];
    public array $addonQuantities = [];
    
    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTimeSlots' => 'required|array|min:1',
        'purpose' => 'required|string|max:500',
        'selectedAddons' => 'array',
        'addonQuantities.*' => 'integer|min:1',
    ];
    
    public function mount(Facility $facility, ?SubFacility $subFacility = null)
    {
        $this->facility = $facility;
        $this->subFacility = $subFacility;
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        
        // Load available add-ons if the facility has add-ons
        if ($this->facility->has_addons) {
            $this->loadAvailableAddons();
        }
        
        $this->loadTimeSlots();
    }
    
    public function loadAvailableAddons()
    {
        $this->availableAddons = $this->facility->addons()
            ->where('is_available', true)
            ->get()
            ->toArray();
            
        // Initialize addon quantities
        foreach ($this->availableAddons as $addon) {
            $this->addonQuantities[$addon['id']] = 1;
        }
    }
    
    public function toggleAddon($addonId)
    {
        if (in_array($addonId, $this->selectedAddons)) {
            $this->selectedAddons = array_diff($this->selectedAddons, [$addonId]);
        } else {
            $this->selectedAddons[] = $addonId;
        }
    }
    
    public function incrementAddonQuantity($addonId)
    {
        $addon = collect($this->availableAddons)->firstWhere('id', $addonId);
        
        if ($addon && ($addon['quantity_available'] === 0 || $this->addonQuantities[$addonId] < $addon['quantity_available'])) {
            $this->addonQuantities[$addonId]++;
        }
    }
    
    public function decrementAddonQuantity($addonId)
    {
        if (isset($this->addonQuantities[$addonId]) && $this->addonQuantities[$addonId] > 1) {
            $this->addonQuantities[$addonId]--;
        }
    }
    
    public function updatedSelectedDate()
    {
        $this->loadTimeSlots();
        $this->selectedTimeSlots = [];
    }
    
    public function loadTimeSlots()
    {
        if ($this->subFacility) {
            // For sub-facilities, we'll use the parent facility's opening/closing times
            // but check bookings specific to this sub-facility
            $parentFacility = $this->facility;
            
            try {
                // Set default times if not available
                $openingTime = $parentFacility->opening_time 
                    ? Carbon::parse($parentFacility->opening_time) 
                    : Carbon::parse('08:00:00');
                    
                $closingTime = $parentFacility->closing_time 
                    ? Carbon::parse($parentFacility->closing_time) 
                    : Carbon::parse('17:00:00');
                    
                // Special handling for midnight (00:00) - treat it as 24:00
                if ($closingTime->format('H:i') === '00:00') {
                    $closingTime = Carbon::parse('23:59:59');
                }
                
                // Make sure closing time is after opening time
                if ($closingTime->lte($openingTime)) {
                    // If closing time is before or equal to opening time, set it to 8 hours after opening time
                    $closingTime = (clone $openingTime)->addHours(8);
                }
                
                // Debug information 
                $this->debug = "Sub-facility booking: {$this->subFacility->id} - {$this->subFacility->name}\n";
                $this->debug .= "Parent Facility: {$parentFacility->id} - {$parentFacility->name}\n";
                $this->debug .= "Opening: {$openingTime->format('H:i')}, Closing: {$closingTime->format('H:i')}";
                
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
                
                // Check which slots are booked for this sub-facility
                $bookings = Booking::where('date', $this->selectedDate)
                    ->where('sub_facility_id', $this->subFacility->id)
                    ->get();
                    
                // Add booking count to debug
                $this->debug .= "\nFound {$bookings->count()} bookings for this date";
                
                // If there are no time slots created, add that to debug
                if (count($timeSlots) === 0) {
                    $this->debug .= "\nNo time slots were generated. Please check facility opening/closing times.";
                } else {
                    $this->debug .= "\nGenerated " . count($timeSlots) . " time slots";
                }
                
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
                
                $this->availableTimeSlots = $timeSlots;
            } catch (\Exception $e) {
                // Handle any errors that occur during time slot generation
                $this->debug = "Error generating time slots: " . $e->getMessage();
                
                // Provide default time slots (9 AM to 5 PM) if there's an error
                $this->availableTimeSlots = $this->generateDefaultTimeSlots();
            }
        } else {
            try {
                $this->availableTimeSlots = $this->facility->getAvailableTimeSlots($this->selectedDate);
                $this->debug = "Main facility booking: {$this->facility->id} - {$this->facility->name}\n";
                $this->debug .= "Generated " . count($this->availableTimeSlots) . " time slots";
            } catch (\Exception $e) {
                $this->debug = "Error generating time slots: " . $e->getMessage();
                $this->availableTimeSlots = $this->generateDefaultTimeSlots();
            }
        }
    }
    
    /**
     * Generate default time slots from 9 AM to 5 PM
     * 
     * @return array Default time slots
     */
    private function generateDefaultTimeSlots()
    {
        $timeSlots = [];
        $start = Carbon::parse('09:00');
        $end = Carbon::parse('17:00');
        
        $currentSlot = clone $start;
        
        while ($currentSlot->lt($end)) {
            $slotEnd = (clone $currentSlot)->addHour();
            
            $timeSlots[] = [
                'start' => $currentSlot->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'formatted' => $currentSlot->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'available' => true,
            ];
            
            $currentSlot->addHour();
        }
        
        return $timeSlots;
    }
    
    public function toggleTimeSlot($index)
    {
        if (!$this->availableTimeSlots[$index]['available']) {
            return;
        }
        
        $slotKey = $this->availableTimeSlots[$index]['start'] . '-' . $this->availableTimeSlots[$index]['end'];
        
        if (in_array($slotKey, $this->selectedTimeSlots)) {
            $this->selectedTimeSlots = array_filter($this->selectedTimeSlots, function($slot) use ($slotKey) {
                return $slot !== $slotKey;
            });
        } else {
            $this->selectedTimeSlots[] = $slotKey;
        }
    }
    
    public function bookFacility()
    {
        $this->validate();
        
        // Sort selected time slots
        sort($this->selectedTimeSlots);
        
        // Group consecutive time slots for more efficient bookings
        $bookingGroups = [];
        $currentGroup = [];
        
        foreach ($this->selectedTimeSlots as $slotKey) {
            list($start, $end) = explode('-', $slotKey);
            
            if (empty($currentGroup)) {
                $currentGroup = ['start' => $start, 'end' => $end];
            } else {
                // If this slot starts when the previous one ends, extend the group
                if ($start === $currentGroup['end']) {
                    $currentGroup['end'] = $end;
                } else {
                    // Otherwise start a new group
                    $bookingGroups[] = $currentGroup;
                    $currentGroup = ['start' => $start, 'end' => $end];
                }
            }
        }
        
        // Add the last group
        if (!empty($currentGroup)) {
            $bookingGroups[] = $currentGroup;
        }
        
        // Create bookings for each group
        foreach ($bookingGroups as $group) {
            $booking = Booking::create([
                'facility_id' => $this->facility->id,
                'sub_facility_id' => $this->subFacility ? $this->subFacility->id : null,
                'user_id' => Auth::id(),
                'date' => $this->selectedDate,
                'start_time' => $group['start'],
                'end_time' => $group['end'],
                'status' => 'pending',
                'notes' => $this->purpose,
            ]);
            
            // Attach selected add-ons to the booking
            if (!empty($this->selectedAddons)) {
                foreach ($this->selectedAddons as $addonId) {
                    if (isset($this->addonQuantities[$addonId])) {
                        $booking->addons()->attach($addonId, [
                            'quantity' => $this->addonQuantities[$addonId],
                        ]);
                    }
                }
            }
        }
        
        session()->flash('message', 'Booking request submitted successfully!');
        $this->reset(['selectedTimeSlots', 'purpose', 'selectedAddons', 'addonQuantities']);
        
        // Reload available add-ons
        if ($this->facility->has_addons) {
            $this->loadAvailableAddons();
        }
        
        $this->loadTimeSlots();
    }
    
    public function render()
    {
        return view('livewire.facility-booking');
    }
}
