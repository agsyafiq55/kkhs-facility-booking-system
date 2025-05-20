<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Image and Basic Info -->
        <div class="md:col-span-1">
            @if($facility->image_path)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="w-full h-auto rounded-lg">
                </div>
            @else
                <div class="mb-6 bg-neutral-200 dark:bg-neutral-700 rounded-lg h-48 flex items-center justify-center">
                    <span class="text-neutral-500 dark:text-neutral-400">No image available</span>
                </div>
            @endif

            <div class="bg-neutral-100 dark:bg-zinc-800 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Facility Status</h3>
                
                @if($facility->status === 'available')
                    <flux:badge color="lime" class="w-full flex justify-center py-2">Available</flux:badge>
                @elseif($facility->status === 'maintenance')
                    <flux:badge color="amber" class="w-full flex justify-center py-2">Maintenance</flux:badge>
                @else
                    <flux:badge color="red" class="w-full flex justify-center py-2">Unavailable</flux:badge>
                @endif
            </div>

            <!-- Additional Facility Options -->
            <div class="mt-4 bg-neutral-100 dark:bg-zinc-800 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Facility Options</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span>Has Add-ons:</span>
                        <span class="{{ $facility->has_addons ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $facility->has_addons ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Has Sub-facilities:</span>
                        <span class="{{ $facility->has_sub_facilities ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $facility->has_sub_facilities ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="md:col-span-2 space-y-6">
            <div>
                <h2 class="text-2xl font-bold">{{ $facility->name }}</h2>
                
                <div class="mt-4">
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Capacity</h3>
                        <p class="mt-1">{{ $facility->capacity ?? 'Not specified' }}</p>
                    </div>
                </div>
                
                <!-- Opening Hours -->
                @if($facility->opening_time || $facility->closing_time)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Operating Hours</h3>
                    <div class="flex space-x-2 mt-1">
                        <div>
                            @if($facility->opening_time)
                                {{ \Carbon\Carbon::parse($facility->opening_time)->format('h:i A') }}
                            @else
                                N/A 
                            @endif
                            -
                            @if($facility->closing_time)
                                {{ \Carbon\Carbon::parse($facility->closing_time)->format('h:i A') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <flux:separator />

            <div>
                <h3 class="text-lg font-semibold mb-2">Description</h3>
                <p class="text-neutral-600 dark:text-neutral-300">{{ $facility->description ?? 'No description available.' }}</p>
            </div>

            <flux:separator />

            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" href="{{ route('admin.facilities.edit', $facility) }}" wire:navigate>Edit</flux:button>
                <flux:modal.trigger name="delete-facility">
                    <flux:button variant="danger">Delete Facility</flux:button>
                </flux:modal.trigger>
            </div>
        </div>
    </div>

    <!-- Addons and Sub-facilities Tabs -->
    @if($facility->has_addons || $facility->has_sub_facilities)
        <div class="mt-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                    @if($facility->has_addons)
                        <button wire:click="$set('activeTab', 'addons')" class="{{ $activeTab === 'addons' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Add-ons
                        </button>
                    @endif
                    
                    @if($facility->has_sub_facilities)
                        <button wire:click="$set('activeTab', 'subfacilities')" class="{{ $activeTab === 'subfacilities' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Sub-facilities
                        </button>
                    @endif
                </nav>
            </div>
            
            <div class="mt-6">
                @if($activeTab === 'addons' && $facility->has_addons)
                    <livewire:admin.facilities.manage-facility-addons :facility="$facility" />
                @endif
                
                @if($activeTab === 'subfacilities' && $facility->has_sub_facilities)
                    <livewire:admin.facilities.manage-sub-facilities :facility="$facility" />
                @endif
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-facility" class="md:w-96">
        <div class="space-y-6 p-6">
            <div>
                <flux:heading size="lg">Delete Facility?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete <strong>{{ $facility->name }}</strong>.</p>
                    <p>This action cannot be undone.</p>
                </flux:text>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="deleteFacility" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="deleteFacility">Delete Facility</span>
                    <span wire:loading wire:target="deleteFacility">Deleting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
