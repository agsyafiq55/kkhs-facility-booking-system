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
    public string $notes = '';
    
    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTimeSlots' => 'required|array|min:1',
        'notes' => 'nullable|string|max:500',
    ];
    
    public function mount(Facility $facility, ?SubFacility $subFacility = null)
    {
        $this->facility = $facility;
        $this->subFacility = $subFacility;
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadTimeSlots();
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
            
            // Generate all possible time slots based on parent facility's opening and closing times
            $openingTime = \Carbon\Carbon::parse($parentFacility->opening_time);
            $closingTime = \Carbon\Carbon::parse($parentFacility->closing_time);
            
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
            
            foreach ($bookings as $booking) {
                $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                
                foreach ($timeSlots as &$slot) {
                    $slotStart = \Carbon\Carbon::parse($slot['start']);
                    $slotEnd = \Carbon\Carbon::parse($slot['end']);
                    
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
        } else {
            $this->availableTimeSlots = $this->facility->getAvailableTimeSlots($this->selectedDate);
        }
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
            Booking::create([
                'facility_id' => $this->facility->id,
                'sub_facility_id' => $this->subFacility ? $this->subFacility->id : null,
                'user_id' => Auth::id(),
                'date' => $this->selectedDate,
                'start_time' => $group['start'],
                'end_time' => $group['end'],
                'status' => 'pending',
                'notes' => $this->notes,
            ]);
        }
        
        session()->flash('message', 'Booking request submitted successfully!');
        $this->reset(['selectedTimeSlots', 'notes']);
        $this->loadTimeSlots();
    }
    
    public function render()
    {
        return view('livewire.facility-booking');
    }
}
