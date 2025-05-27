<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use League\Csv\Writer;

class BookingsList extends Component
{
    use WithPagination;

    public $status = '';
    public $dateRange = 'all';
    public $search = '';
    
    protected $queryString = ['status', 'dateRange', 'search'];

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
        
        // Search functionality
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('user', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('facility', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('subFacility', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }
        
        // Order by booking date
        $bookings = $query->orderBy('date')->orderBy('start_time')->paginate(10);
        
        // Get counts for dashboard stats
        $pendingCount = Booking::where('status', 'pending')->count();
        $todayCount = Booking::whereDate('date', Carbon::today())->count();
        $weekCount = Booking::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            
        return view('livewire.admin.bookings.bookings-list', [
            'bookings' => $bookings,
            'pendingCount' => $pendingCount,
            'todayCount' => $todayCount,
            'weekCount' => $weekCount
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
    
    /**
     * Approve a booking
     */
    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = 'approved';
        $booking->save();
        
        // Add success message
        session()->flash('success', 'Booking #' . $bookingId . ' has been approved successfully.');
        
        // Send notification to user (you can implement this)
        // $booking->user->notify(new BookingStatusChanged($booking));
    }
    
    /**
     * Reject a booking
     */
    public function rejectBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = 'rejected';
        $booking->save();
        
        // Add success message
        session()->flash('success', 'Booking #' . $bookingId . ' has been rejected.');
        
        // Send notification to user (you can implement this)
        // $booking->user->notify(new BookingStatusChanged($booking));
    }
    
    /**
     * Export bookings as CSV
     */
    public function exportBookings()
    {
        $query = Booking::with(['facility', 'subFacility', 'user']);
        
        // Apply the same filters as the current view
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
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
        
        $bookings = $query->orderBy('date')->orderBy('start_time')->get();
        
        // Prepare CSV data
        $csvData = [
            ['ID', 'Facility', 'Sub-Facility', 'User', 'Email', 'Date', 'Start Time', 'End Time', 'Status', 'Purpose']
        ];
        
        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->id,
                $booking->facility->name,
                $booking->subFacility ? $booking->subFacility->name : 'N/A',
                $booking->user->name,
                $booking->user->email,
                $booking->date->format('Y-m-d'),
                $booking->start_time,
                $booking->end_time,
                $booking->status,
                $booking->notes
            ];
        }
        
        // Create a temporary file
        $csv = Writer::createFromString('');
        $csv->insertAll($csvData);
        
        $filename = 'bookings-export-' . date('Y-m-d') . '.csv';
        
        return response()->streamDownload(function() use ($csv) {
            echo $csv->toString();
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
} 