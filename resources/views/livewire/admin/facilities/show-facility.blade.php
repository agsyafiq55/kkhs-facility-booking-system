<div>
    <div class="bg-neutral-100 dark:bg-zinc-800 rounded-lg p-6">
        <!-- Header with Edit/Delete buttons -->
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <div class="flex space-x-2">
                <flux:button href="{{ route('admin.facilities.edit', $facility) }}" wire:navigate>Edit</flux:button>
                <flux:modal.trigger name="delete-facility">
                    <flux:button variant="danger">Delete</flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Main content grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left column - Facility image -->
            <div class="rounded-md shadow flex items-center justify-center h-full">
                @if($facility->image_path)
                <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover rounded-md">
                @else
                <div class="bg-neutral-200 dark:bg-neutral-700 rounded-md h-full w-full flex items-center justify-center">
                    <span class="text-neutral-500 dark:text-neutral-400">No image available</span>
                </div>
                @endif
            </div>

            <!-- Right column - Facility details -->
            <div class="space-y-4">
                <div>
                    <flux:heading size="xl" class="font-semibold">{{ $facility->name }}</flux:heading>
                </div>

                <div>
                    <flux:heading size="lg">Capacity</flux:heading>
                    <flux:text>{{ $facility->capacity ?? 'Not specified' }}</flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Operating Hours</flux:heading>
                    <flux:text>
                        @if($facility->opening_time && $facility->closing_time)
                        {{ \Carbon\Carbon::parse($facility->opening_time)->format('h:i A') }} -
                        {{ \Carbon\Carbon::parse($facility->closing_time)->format('h:i A') }}
                        @else
                        Not specified
                        @endif
                    </flux:text>
                </div>

                <div>
                    <flux:heading size="lg">Facility Status</flux:heading>
                    @if($facility->status === 'available')
                    <flux:badge color="lime">Available</flux:badge>
                    @elseif($facility->status === 'maintenance')
                    <flux:badge color="amber">Maintenance</flux:badge>
                    @else
                    <flux:badge color="red">Unavailable</flux:badge>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description section -->
        <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-3">
            <flux:heading size="lg" class="font-semibold">Description</flux:heading>
            <flux:text class="mt-2 text-justify">{{ $facility->description ?? 'No description available.' }}</flux:text>
        </div>

        <!-- Facility Add-ons and Sub-facilities -->
        @if($facility->has_addons || $facility->has_sub_facilities)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($facility->has_addons)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-3">
                <flux:heading size="lg" class="font-semibold">Available Add-ons</flux:heading>
                
                @if(isset($facility->addons) && count($facility->addons) > 0)
                <ul class="mt-2 space-y-2">
                    @foreach($facility->addons as $addon)
                    <li class="flex items-center justify-between">
                        <flux:text>{{ $addon->name }}</flux:text>
                    </li>
                    @endforeach
                </ul>
                @else
                <flux:callout icon="information-circle" class="mt-2">
                    <flux:callout.text>
                        Add-ons coming soon!
                    </flux:callout.text>
                </flux:callout>
                @endif
            </div>
            @endif

            @if($facility->has_sub_facilities)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-3">
                <flux:heading size="lg" class="font-semibold">Sub-facilities</flux:heading>
                
                @if(isset($facility->subFacilities) && count($facility->subFacilities) > 0)
                <ul class="mt-2 space-y-2">
                    @foreach($facility->subFacilities as $subFacility)
                    <li class="flex items-center justify-between">
                        <flux:text>{{ $subFacility->name }}</flux:text>
                        <flux:badge color="{{ $subFacility->status === 'available' ? 'lime' : ($subFacility->status === 'maintenance' ? 'amber' : 'red') }}">
                            {{ ucfirst($subFacility->status) }}
                        </flux:badge>
                    </li>
                    @endforeach
                </ul>
                @else
                <flux:callout icon="information-circle" class="mt-2">
                    <flux:callout.text>
                        Sub-facilities coming soon!
                    </flux:callout.text>
                </flux:callout>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Additional Facility Options -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-3">
                <flux:heading size="lg" class="font-semibold mb-2">Facility Options</flux:heading>

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
        
        <!-- Booking Section -->
        @if($facility->status === 'available')
            @if(!$facility->has_sub_facilities)
                <livewire:facility-booking :facility="$facility" />
            @else
                <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                    <flux:heading size="lg" class="mb-4">Book Sub-facilities</flux:heading>
                    <flux:callout icon="information-circle">
                        <flux:callout.text>
                            This facility has sub-facilities that can be booked individually. Please select a sub-facility to book.
                        </flux:callout.text>
                    </flux:callout>
                    
                    @if(isset($facility->subFacilities) && count($facility->subFacilities) > 0)
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($facility->subFacilities as $subFacility)
                                <div class="border rounded-lg p-4 {{ $subFacility->status !== 'available' ? 'opacity-50' : '' }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <flux:heading size="sm">{{ $subFacility->name }}</flux:heading>
                                        <flux:badge color="{{ $subFacility->status === 'available' ? 'lime' : ($subFacility->status === 'maintenance' ? 'amber' : 'red') }}">
                                            {{ ucfirst($subFacility->status) }}
                                        </flux:badge>
                                    </div>
                                    
                                    @if($subFacility->status === 'available')
                                        <flux:modal.trigger name="book-subfacility-{{ $subFacility->id }}">
                                            <flux:button variant="primary" class="w-full mt-2">Book Now</flux:button>
                                        </flux:modal.trigger>
                                        
                                        <flux:modal name="book-subfacility-{{ $subFacility->id }}" class="max-w-4xl">
                                            <flux:heading size="lg" class="mb-4">Book {{ $subFacility->name }}</flux:heading>
                                            <livewire:facility-booking :facility="$facility" :subFacility="$subFacility" wire:key="booking-{{ $subFacility->id }}" />
                                        </flux:modal>
                                    @else
                                        <flux:button disabled class="w-full mt-2 opacity-50 cursor-not-allowed">Not Available</flux:button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @else
            <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                <flux:callout icon="exclamation-triangle">
                    <flux:callout.heading>Facility Not Available</flux:callout.heading>
                    <flux:callout.text>
                        This facility is currently not available for booking.
                    </flux:callout.text>
                </flux:callout>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-facility" class="md:w-96">
        <div class="py-2">
            <flux:heading size="lg">Delete Facility?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete <strong>{{ $facility->name }}</strong>. This action cannot be undone.</p>
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
    </flux:modal>
</div>