<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class BookingsList extends Component
{
    use WithPagination;

    public $status = '';
    public $dateRange = 'all';
    
    protected $queryString = ['status', 'dateRange'];

    public function render()
    {
        $query = Booking::with(['facility', 'subFacility', 'user', 'addons']);
        
        // Filter by status if provided
        if ($this->status && in_array($this->status, ['pending', 'approved', 'rejected', 'cancelled'])) {
            $query->where('status', $this->status);
        }
        
        // Filter by date range
        switch ($this->dateRange) {
            case 'day':
                $query->whereDate('date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('date', Carbon::now()->month)
                      ->whereYear('date', Carbon::now()->year);
                break;
        }
        
        // Order by booking date
        $bookings = $query->orderBy('date')->orderBy('start_time')->paginate(10);
            
        return view('livewire.admin.bookings.bookings-list', [
            'bookings' => $bookings
        ]);
    }

    public function filterByStatus($status = '')
    {
        $this->status = $status;
        $this->resetPage();
    }
    
    public function filterByDateRange($range = 'all')
    {
        $this->dateRange = $range;
        $this->resetPage();
    }
} 