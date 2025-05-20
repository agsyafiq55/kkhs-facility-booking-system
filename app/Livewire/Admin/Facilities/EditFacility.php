<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;

class EditFacility extends Component
{
    use WithFileUploads;
    
    public Facility $facility;
    
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|string')]
    public $description = '';
    
    #[Rule('nullable|integer|min:1')]
    public $capacity = null;
    
    #[Rule('required|string|in:available,maintenance,unavailable')]
    public $status = '';
    
    #[Rule('nullable|image|mimes:jpeg,png,jpg,gif|max:2048')]
    public $image = null;
    
    #[Rule('nullable|date_format:H:i')]
    public $opening_time = null;
    
    #[Rule('nullable|date_format:H:i')]
    public $closing_time = null;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
        $this->name = $facility->name;
        $this->description = $facility->description;
        $this->capacity = $facility->capacity;
        $this->status = $facility->status;
        $this->opening_time = $facility->opening_time;
        $this->closing_time = $facility->closing_time;
    }
    
    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,maintenance,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
        ]);
        
        if ($this->image) {
            // Delete old image if exists
            if ($this->facility->image_path && Storage::disk('public')->exists($this->facility->image_path)) {
                Storage::disk('public')->delete($this->facility->image_path);
            }
            
            $validated['image_path'] = $this->image->store('facilities', 'public');
        }
        
        $this->facility->update($validated);
        
        session()->flash('success', 'Facility updated successfully.');
        
        return $this->redirect(route('admin.facilities.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.admin.facilities.edit-facility');
    }
}
