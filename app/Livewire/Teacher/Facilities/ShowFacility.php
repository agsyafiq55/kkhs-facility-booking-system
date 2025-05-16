<?php

namespace App\Livewire\Teacher\Facilities;

use App\Models\Facility;
use Livewire\Component;

class ShowFacility extends Component
{
    public Facility $facility;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
    }
    
    public function render()
    {
        return view('livewire.teacher.facilities.show-facility');
    }
} 