<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingsList extends Component
{
    use WithPagination;

    public $status = '';
    
    protected $queryString = ['status'];

    public function render()
    {
        $query = Booking::with(['facility', 'subFacility', 'user']);
        
        // Filter by status if provided
        if ($this->status && in_array($this->status, ['pending', 'approved', 'rejected', 'cancelled'])) {
            $query->where('status', $this->status);
        }
        
        $bookings = $query->latest()->paginate(10);
            
        return view('livewire.admin.bookings.bookings-list', [
            'bookings' => $bookings
        ]);
    }

    public function filterByStatus($status = '')
    {
        $this->status = $status;
        $this->resetPage();
    }
} 