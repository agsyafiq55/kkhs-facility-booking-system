<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ShowFacility extends Component
{
    public Facility $facility;
    public $activeTab = 'addons';
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
        
        // Set default active tab based on availability
        if (!$facility->has_addons && $facility->has_sub_facilities) {
            $this->activeTab = 'subfacilities';
        }
    }
    
    public function deleteFacility()
    {
        // Delete associated image if exists
        if ($this->facility->image_path && Storage::disk('public')->exists($this->facility->image_path)) {
            Storage::disk('public')->delete($this->facility->image_path);
        }
        
        // Delete any add-ons
        foreach ($this->facility->addons as $addon) {
            $addon->delete();
        }
        
        // Delete any sub-facilities and their images
        foreach ($this->facility->subFacilities as $subFacility) {
            if ($subFacility->image_path && Storage::disk('public')->exists($subFacility->image_path)) {
                Storage::disk('public')->delete($subFacility->image_path);
            }
            $subFacility->delete();
        }
        
        $this->facility->delete();
        
        session()->flash('success', 'Facility deleted successfully.');
        
        return $this->redirect(route('admin.facilities.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.admin.facilities.show-facility');
    }
}
