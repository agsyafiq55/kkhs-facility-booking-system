<div>
    <div class="flex justify-between items-center mb-4">
        <flux:heading>Manage Sub-facilities for {{ $facility->name }}</flux:heading>
        <flux:button wire:click="addSubFacility">Add New Sub-facility</flux:button>
    </div>

    @if ($facility->subFacilities->isEmpty())
        <flux:callout icon="information-circle">
            <flux:callout.heading>No Sub-facilities Available</flux:callout.heading>
            <flux:callout.text>
                This facility doesn't have any sub-facilities yet. Sub-facilities are separate bookable 
                areas within the main facility (e.g., Court 1, Court 2, etc.).
            </flux:callout.text>
        </flux:callout>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($subFacilities as $subFacility)
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
                    <div class="h-48 w-full overflow-hidden">
                        @if ($subFacility->image_path)
                            <img src="{{ Storage::url($subFacility->image_path) }}" alt="{{ $subFacility->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-neutral-100 dark:bg-zinc-700">
                                <svg class="w-16 h-16 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $subFacility->name }}</h3>
                                @if ($subFacility->capacity)
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Capacity: {{ $subFacility->capacity }}</p>
                                @endif
                            </div>
                            <div>
                                @if ($subFacility->status === 'available')
                                    <flux:badge color="lime">Available</flux:badge>
                                @elseif ($subFacility->status === 'maintenance')
                                    <flux:badge color="amber">Maintenance</flux:badge>
                                @else
                                    <flux:badge color="rose">Unavailable</flux:badge>
                                @endif
                            </div>
                        </div>
                        
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($subFacility->description, 100) }}</p>
                        
                        <div class="mt-4 flex space-x-2">
                            <flux:button variant="subtle" wire:click="editSubFacility({{ $subFacility->id }})">Edit</flux:button>
                            <flux:button variant="danger" wire:click="$dispatch('confirm-delete', { id: {{ $subFacility->id }}, type: 'sub-facility', action: 'delete-sub-facility' })">Delete</flux:button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    
        <div class="mt-4">
            {{ $subFacilities->links() }}
        </div>
    @endif

    <!-- Modal for Add/Edit Sub-facility -->
    <flux:modal wire:model="isModalOpen" class="md:max-w-lg">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editing ? 'Edit Sub-facility' : 'Add New Sub-facility' }}</flux:heading>
            
            <div class="grid grid-cols-1 gap-4">
                <flux:field>
                    <flux:text for="name">Name</flux:text>
                    <flux:input id="name" wire:model="name" required />
                    <flux:error name="name" />
                </flux:field>
                
                <flux:field>
                    <flux:text for="capacity">Capacity</flux:text>
                    <flux:input id="capacity" wire:model="capacity" type="number" min="1" />
                    <flux:error name="capacity" />
                </flux:field>
                
                <flux:field>
                    <flux:text for="status">Status</flux:text>
                    <flux:select id="status" wire:model="status">
                        <flux:select.option value="available">Available</flux:select.option>
                        <flux:select.option value="maintenance">Maintenance</flux:select.option>
                        <flux:select.option value="unavailable">Unavailable</flux:select.option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <flux:separator variant="subtle" />
                
                <flux:field variant="inline">
                    <flux:switch wire:model.live="is_bookable" label="Bookable" description="Allow bookings for this sub-facility." />
                    <flux:error name="is_bookable" />
                </flux:field>

                <flux:separator variant="subtle" />
            </div>
            
            <flux:field>
                <flux:text for="description">Description</flux:text>
                <flux:textarea id="description" wire:model="description" rows="3"></flux:textarea>
                <flux:error name="description" />
            </flux:field>
            
            <flux:field>
                <flux:label for="image">Image</flux:label>
                <input type="file" id="image" wire:model="image" class="block w-full text-sm text-neutral-500 dark:text-neutral-400 
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-neutral-50 file:text-neutral-700
                    dark:file:bg-zinc-700 dark:file:text-neutral-100
                    hover:file:bg-neutral-100 dark:hover:file:bg-zinc-600">
                <div wire:loading wire:target="image" class="mt-2 text-sm text-neutral-500">Uploading...</div>
                <flux:error name="image" />
            </flux:field>
            
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="$set('isModalOpen', false)">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
