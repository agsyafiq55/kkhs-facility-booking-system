<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class FacilityList extends Component
{
    use WithPagination;

    public $search = '';
    
    protected $queryString = [
        'search' => ['except' => '']
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function deleteFacility($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        
        // Delete associated image if exists
        if ($facility->image_path && Storage::disk('public')->exists($facility->image_path)) {
            Storage::disk('public')->delete($facility->image_path);
        }
        
        $facility->delete();
        
        session()->flash('success', 'Facility deleted successfully.');
    }
    
    public function render()
    {
        $facilities = Facility::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.admin.facilities.facility-list', [
            'facilities' => $facilities
        ]);
    }
}
