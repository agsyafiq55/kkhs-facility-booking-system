<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ShowFacility extends Component
{
    public Facility $facility;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
    }
    
    public function deleteFacility()
    {
        // Delete associated image if exists
        if ($this->facility->image_path && Storage::disk('public')->exists($this->facility->image_path)) {
            Storage::disk('public')->delete($this->facility->image_path);
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
