<?php

namespace App\Livewire\Teacher\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithPagination;

class FacilityList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $facilities = Facility::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(12);
            
        return view('livewire.teacher.facilities.facility-list', [
            'facilities' => $facilities
        ]);
    }
} 