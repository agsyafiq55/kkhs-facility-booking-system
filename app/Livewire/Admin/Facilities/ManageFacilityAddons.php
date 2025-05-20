<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use App\Models\FacilityAddon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;

class ManageFacilityAddons extends Component
{
    use WithPagination;
    
    public Facility $facility;
    
    public ?FacilityAddon $editing = null;
    
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|string')]
    public $description = '';
    
    #[Rule('boolean')]
    public $is_available = true;
    
    #[Rule('integer|min:0')]
    public $quantity_available = 0;
    
    public $isModalOpen = false;
    
    public function mount(Facility $facility)
    {
        $this->facility = $facility;
    }
    
    public function addAddon()
    {
        $this->resetValidation();
        $this->resetExcept('facility');
        $this->editing = null;
        $this->isModalOpen = true;
    }
    
    public function editAddon(FacilityAddon $addon)
    {
        $this->resetValidation();
        $this->editing = $addon;
        $this->name = $addon->name;
        $this->description = $addon->description;
        $this->is_available = $addon->is_available;
        $this->quantity_available = $addon->quantity_available;
        $this->isModalOpen = true;
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        if ($this->editing) {
            $this->editing->update($validated);
            $message = 'Add-on updated successfully';
        } else {
            $this->facility->addons()->create($validated);
            $message = 'Add-on created successfully';
        }
        
        $this->isModalOpen = false;
        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);
    }
    
    #[On('delete-addon')]
    public function deleteAddon(FacilityAddon $addon)
    {
        $addon->delete();
        
        $this->dispatch('notify', [
            'message' => 'Add-on deleted successfully',
            'type' => 'success'
        ]);
    }
    
    public function render()
    {
        $addons = $this->facility->addons()->paginate(10);
        
        return view('livewire.admin.facilities.manage-facility-addons', [
            'addons' => $addons
        ]);
    }
}
