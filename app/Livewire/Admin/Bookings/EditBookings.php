<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;

class EditBookings extends Component
{
    public Booking $booking;
    public $status;
    public $purpose;
    public $specialRequests;
    
    protected $rules = [
        'status' => 'required|in:pending,approved,rejected,cancelled',
        'purpose' => 'nullable|string|max:500',
        'specialRequests' => 'nullable|string|max:500',
    ];
    
    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->status = $booking->status;
        $this->purpose = $booking->purpose;
        $this->specialRequests = $booking->special_requests;
    }
    
    public function updateBooking()
    {
        $this->validate();
        
        $this->booking->update([
            'status' => $this->status,
            'purpose' => $this->purpose,
            'special_requests' => $this->specialRequests,
        ]);
        
        session()->flash('success', 'Booking updated successfully.');
        return redirect()->route('admin.bookings.index');
    }
    
    public function deleteBooking()
    {
        $this->booking->delete();
        
        session()->flash('success', 'Booking deleted successfully.');
        return redirect()->route('admin.bookings.index');
    }
    
    public function render()
    {
        return view('livewire.admin.bookings.edit-bookings');
    }
} 