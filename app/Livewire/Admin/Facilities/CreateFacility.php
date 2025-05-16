<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

class CreateFacility extends Component
{
    use WithFileUploads;
    
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|string')]
    public $description = '';
    
    #[Rule('nullable|integer|min:1')]
    public $capacity = null;
    
    #[Rule('required|string|in:available,maintenance,unavailable')]
    public $status = 'available';
    
    #[Rule('nullable|image|mimes:jpeg,png,jpg,gif|max:2048')]
    public $image = null;
    
    #[Rule('nullable|array')]
    public $features = [];
    
    public $availableFeatures = [
        'projector' => 'Projector',
        'whiteboard' => 'Whiteboard',
        'air_conditioning' => 'Air Conditioning',
        'wifi' => 'WiFi'
    ];
    
    public function save()
    {
        $validated = $this->validate();
        
        if ($this->image) {
            $validated['image_path'] = $this->image->store('facilities', 'public');
        }
        
        Facility::create($validated);
        
        session()->flash('success', 'Facility created successfully.');
        
        return $this->redirect(route('admin.facilities.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.admin.facilities.create-facility');
    }
}
