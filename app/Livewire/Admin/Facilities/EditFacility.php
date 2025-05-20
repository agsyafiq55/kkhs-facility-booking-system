<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EditFacility extends Component
{
    use WithFileUploads;
    
    public Facility $facility;
    public $activeTab = 'addons';
    
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
    
    public $opening_time = null;
    public $closing_time = null;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
        $this->name = $facility->name;
        $this->description = $facility->description;
        $this->capacity = $facility->capacity;
        $this->status = $facility->status;
        
        // Format times to display in 12-hour format if they exist
        if ($facility->opening_time) {
            $this->opening_time = Carbon::parse($facility->opening_time)->format('g:i A');
        }
        
        if ($facility->closing_time) {
            $this->closing_time = Carbon::parse($facility->closing_time)->format('g:i A');
        }
        
        // Set default active tab based on availability
        if (!$facility->has_addons && $facility->has_sub_facilities) {
            $this->activeTab = 'subfacilities';
        }
    }
    
    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,maintenance,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
        ]);
        
        // Convert 12-hour format to 24-hour format for database storage
        if (!empty($validated['opening_time'])) {
            try {
                $validated['opening_time'] = Carbon::parse($validated['opening_time'])->format('H:i');
            } catch (\Exception $e) {
                session()->flash('error', 'Invalid opening time format. Please use format like "9:30 AM".');
                return null;
            }
        }
        
        if (!empty($validated['closing_time'])) {
            try {
                $validated['closing_time'] = Carbon::parse($validated['closing_time'])->format('H:i');
            } catch (\Exception $e) {
                session()->flash('error', 'Invalid closing time format. Please use format like "5:00 PM".');
                return null;
            }
        }
        
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
