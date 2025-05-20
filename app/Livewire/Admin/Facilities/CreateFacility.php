<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use App\Models\FacilityAddon;
use App\Models\SubFacility;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CreateFacility extends Component
{
    use WithFileUploads;
    
    // Facility fields
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
    public $has_addons = false;
    
    #[Rule('boolean')]
    public $has_sub_facilities = false;
    
    public $opening_time = null;
    public $closing_time = null;
    
    // Add-ons management
    public $addons = [];
    public $addonName = '';
    public $addonDescription = '';
    public $addonQuantity = 0;
    public $addonIsAvailable = true;
    public $editingAddonIndex = null;
    
    // Sub-facilities management
    public $subFacilities = [];
    public $subFacilityName = '';
    public $subFacilityDescription = '';
    public $subFacilityCapacity = null;
    public $subFacilityStatus = 'available';
    public $subFacilityImage = null;
    public $subFacilityIsBookable = true;
    public $editingSubFacilityIndex = null;
    public $subFacilityImages = [];
    
    // Add-on methods
    public function addAddon()
    {
        $this->validate([
            'addonName' => 'required|string|max:255',
            'addonDescription' => 'nullable|string',
            'addonQuantity' => 'integer|min:0',
            'addonIsAvailable' => 'boolean',
        ]);
        
        if ($this->editingAddonIndex !== null) {
            $this->addons[$this->editingAddonIndex] = [
                'name' => $this->addonName,
                'description' => $this->addonDescription,
                'quantity_available' => $this->addonQuantity,
                'is_available' => $this->addonIsAvailable,
            ];
        } else {
            $this->addons[] = [
                'name' => $this->addonName,
                'description' => $this->addonDescription,
                'quantity_available' => $this->addonQuantity,
                'is_available' => $this->addonIsAvailable,
            ];
        }
        
        $this->resetAddonForm();
    }
    
    public function editAddon($index)
    {
        $this->editingAddonIndex = $index;
        $this->addonName = $this->addons[$index]['name'];
        $this->addonDescription = $this->addons[$index]['description'];
        $this->addonQuantity = $this->addons[$index]['quantity_available'];
        $this->addonIsAvailable = $this->addons[$index]['is_available'];
    }
    
    public function removeAddon($index)
    {
        unset($this->addons[$index]);
        $this->addons = array_values($this->addons);
        $this->resetAddonForm();
    }
    
    public function resetAddonForm()
    {
        $this->addonName = '';
        $this->addonDescription = '';
        $this->addonQuantity = 0;
        $this->addonIsAvailable = true;
        $this->editingAddonIndex = null;
    }
    
    // Sub-facility methods
    public function addSubFacility()
    {
        $this->validate([
            'subFacilityName' => 'required|string|max:255',
            'subFacilityDescription' => 'nullable|string',
            'subFacilityCapacity' => 'nullable|integer|min:1',
            'subFacilityStatus' => 'required|string|in:available,maintenance,unavailable',
            'subFacilityImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subFacilityIsBookable' => 'boolean',
        ]);
        
        $imagePath = null;
        if ($this->subFacilityImage) {
            $imagePath = 'temp-' . time() . '-' . $this->subFacilityImage->getClientOriginalName();
            $this->subFacilityImage->storeAs('public/temp', $imagePath);
            $this->subFacilityImages[] = $imagePath;
        }
        
        if ($this->editingSubFacilityIndex !== null) {
            $this->subFacilities[$this->editingSubFacilityIndex] = [
                'name' => $this->subFacilityName,
                'description' => $this->subFacilityDescription,
                'capacity' => $this->subFacilityCapacity,
                'status' => $this->subFacilityStatus,
                'is_bookable' => $this->subFacilityIsBookable,
                'image_path' => $imagePath ?? $this->subFacilities[$this->editingSubFacilityIndex]['image_path'] ?? null,
            ];
        } else {
            $this->subFacilities[] = [
                'name' => $this->subFacilityName,
                'description' => $this->subFacilityDescription,
                'capacity' => $this->subFacilityCapacity,
                'status' => $this->subFacilityStatus,
                'is_bookable' => $this->subFacilityIsBookable,
                'image_path' => $imagePath,
            ];
        }
        
        $this->resetSubFacilityForm();
    }
    
    public function editSubFacility($index)
    {
        $this->editingSubFacilityIndex = $index;
        $this->subFacilityName = $this->subFacilities[$index]['name'];
        $this->subFacilityDescription = $this->subFacilities[$index]['description'] ?? '';
        $this->subFacilityCapacity = $this->subFacilities[$index]['capacity'];
        $this->subFacilityStatus = $this->subFacilities[$index]['status'];
        $this->subFacilityIsBookable = $this->subFacilities[$index]['is_bookable'];
    }
    
    public function removeSubFacility($index)
    {
        // Remove temporary image if exists
        if (!empty($this->subFacilities[$index]['image_path'])) {
            Storage::delete('public/temp/' . $this->subFacilities[$index]['image_path']);
            
            // Remove from the images array
            $key = array_search($this->subFacilities[$index]['image_path'], $this->subFacilityImages);
            if ($key !== false) {
                unset($this->subFacilityImages[$key]);
            }
        }
        
        unset($this->subFacilities[$index]);
        $this->subFacilities = array_values($this->subFacilities);
        $this->resetSubFacilityForm();
    }
    
    public function resetSubFacilityForm()
    {
        $this->subFacilityName = '';
        $this->subFacilityDescription = '';
        $this->subFacilityCapacity = null;
        $this->subFacilityStatus = 'available';
        $this->subFacilityImage = null;
        $this->subFacilityIsBookable = true;
        $this->editingSubFacilityIndex = null;
    }
    
    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,maintenance,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_addons' => 'boolean',
            'has_sub_facilities' => 'boolean',
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
            $validated['image_path'] = $this->image->store('facilities', 'public');
        }
        
        if ($this->has_addons && count($this->addons) === 0) {
            session()->flash('error', 'You must add at least one add-on.');
            return;
        }
        
        if ($this->has_sub_facilities && count($this->subFacilities) === 0) {
            session()->flash('error', 'You must add at least one sub-facility.');
            return;
        }
        
        $facility = Facility::create($validated);
        
        // Create add-ons
        if ($this->has_addons) {
            foreach ($this->addons as $addon) {
                $facility->addons()->create($addon);
            }
        }
        
        // Create sub-facilities
        if ($this->has_sub_facilities) {
            foreach ($this->subFacilities as $subFacility) {
                $imagePath = null;
                
                if (!empty($subFacility['image_path'])) {
                    // Move from temp to permanent storage
                    $newPath = 'sub-facilities/' . $subFacility['image_path'];
                    if (Storage::disk('public')->exists('temp/' . $subFacility['image_path'])) {
                        Storage::disk('public')->copy('temp/' . $subFacility['image_path'], $newPath);
                        Storage::disk('public')->delete('temp/' . $subFacility['image_path']);
                        $subFacility['image_path'] = $newPath;
                    }
                }
                
                $facility->subFacilities()->create($subFacility);
            }
        }
        
        // Clean up any temporary images
        foreach ($this->subFacilityImages as $image) {
            if (Storage::disk('public')->exists('temp/' . $image)) {
                Storage::disk('public')->delete('temp/' . $image);
            }
        }
        
        session()->flash('success', 'Facility created successfully.');
        
        return $this->redirect(route('admin.facilities.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.admin.facilities.create-facility');
    }
}
