<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use App\Models\SubFacility;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class ManageSubFacilities extends Component
{
    use WithPagination, WithFileUploads;
    
    public Facility $facility;
    
    public ?SubFacility $editing = null;
    
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
    
    #[Rule('boolean')]
    public $is_bookable = true;
    
    public $isModalOpen = false;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
    }
    
    public function addSubFacility()
    {
        $this->resetValidation();
        $this->resetExcept('facility');
        $this->editing = null;
        $this->isModalOpen = true;
    }
    
    public function editSubFacility(SubFacility $subFacility)
    {
        $this->resetValidation();
        $this->editing = $subFacility;
        $this->name = $subFacility->name;
        $this->description = $subFacility->description;
        $this->capacity = $subFacility->capacity;
        $this->status = $subFacility->status;
        $this->is_bookable = $subFacility->is_bookable;
        $this->isModalOpen = true;
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        if ($this->image) {
            if ($this->editing && $this->editing->image_path && Storage::disk('public')->exists($this->editing->image_path)) {
                Storage::disk('public')->delete($this->editing->image_path);
            }
            
            $validated['image_path'] = $this->image->store('sub-facilities', 'public');
        }
        
        if ($this->editing) {
            $this->editing->update($validated);
            $message = 'Sub-facility updated successfully';
        } else {
            $this->facility->subFacilities()->create($validated);
            $message = 'Sub-facility created successfully';
        }
        
        $this->isModalOpen = false;
        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);
    }
    
    #[On('delete-sub-facility')]
    public function deleteSubFacility(SubFacility $subFacility)
    {
        if ($subFacility->image_path && Storage::disk('public')->exists($subFacility->image_path)) {
            Storage::disk('public')->delete($subFacility->image_path);
        }
        
        $subFacility->delete();
        
        $this->dispatch('notify', [
            'message' => 'Sub-facility deleted successfully',
            'type' => 'success'
        ]);
    }
    
    public function render()
    {
        $subFacilities = $this->facility->subFacilities()->paginate(10);
        
        return view('livewire.admin.facilities.manage-sub-facilities', [
            'subFacilities' => $subFacilities
        ]);
    }
}
