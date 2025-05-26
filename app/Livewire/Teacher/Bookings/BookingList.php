<?php

namespace App\Livewire\Teacher\Bookings;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BookingList extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $status = '';
    public string $dateRange = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateRange' => ['except' => ''],
    ];
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedStatus()
    {
        $this->resetPage();
    }
    
    public function updatedDateRange()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = Booking::query()
            ->where('user_id', Auth::id())
            ->with(['facility', 'subFacility'])
            ->latest('date');
            
        if ($this->search) {
            $query->where(function ($query) {
                $query->whereHas('facility', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('subFacility', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->dateRange) {
            // Parse date range (expecting format like "2023-01-01 to 2023-01-31")
            $dates = explode(' to ', $this->dateRange);
            if (count($dates) === 2) {
                $query->whereBetween('date', $dates);
            }
        }
        
        $bookings = $query->paginate(10);
        
        return view('livewire.teacher.bookings.booking-list', [
            'bookings' => $bookings,
        ]);
    }
    
    public function cancelBooking(Booking $booking)
    {
        // Make sure the booking belongs to this user
        if ($booking->user_id !== Auth::id()) {
            return;
        }
        
        $booking->update(['status' => 'cancelled']);
        
        session()->flash('message', 'Booking cancelled successfully.');
    }
} 