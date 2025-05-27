<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main content grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left column - Facility image -->
            <div class="md:col-span-2 rounded-lg shadow-md overflow-hidden aspect-video">
                @if($facility->image_path)
                <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                @else
                <div class="bg-neutral-200 dark:bg-neutral-700 h-full w-full flex items-center justify-center">
                    <span class="text-neutral-500 dark:text-neutral-400">No image available</span>
                </div>
                @endif
            </div>

            <!-- Right column - Facility details -->
            <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6 space-y-6">
                <div class="mb-3">
                    <h1 class="text-3xl font-bold">{{ $facility->name }}</h1>
                </div>

                <flux:separator />
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Facility Status</div>
                    @if($facility->status === 'available')
                    <flux:badge color="lime">Available</flux:badge>
                    @elseif($facility->status === 'maintenance')
                    <flux:badge color="amber">Maintenance</flux:badge>
                    @else
                    <flux:badge color="red">Unavailable</flux:badge>
                    @endif
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Capacity</div>
                    <flux:text>{{ $facility->capacity ?? 'Not specified' }}</flux:text>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Operating Hours</div>
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
                    <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Facility Options</div>
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
        </div>

        <!-- Description section -->
        <div class="mt-8 bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
            <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Description</div>
            <flux:text class="text-justify leading-relaxed">{{ $facility->description ?? 'No description available.' }}</flux:text>
        </div>

        <!-- Facility Add-ons and Sub-facilities -->
        @if($facility->has_addons || $facility->has_sub_facilities)
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            @if($facility->has_addons)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
                <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Available Add-ons</div>

                @if(isset($facility->addons) && count($facility->addons) > 0)
                <ul class="space-y-3">
                    @foreach($facility->addons as $addon)
                    <li class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-zinc-800 rounded-md">
                        <flux:text>{{ $addon->name }}</flux:text>
                    </li>
                    @endforeach
                </ul>
                @else
                <flux:callout icon="information-circle">
                    <flux:callout.text>
                        Add-ons coming soon!
                    </flux:callout.text>
                </flux:callout>
                @endif
            </div>
            @endif

            @if($facility->has_sub_facilities)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
                <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Sub-facilities</div>

                @if(isset($facility->subFacilities) && count($facility->subFacilities) > 0)
                <ul class="space-y-3">
                    @foreach($facility->subFacilities as $subFacility)
                    <li class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-zinc-800 rounded-md">
                        <flux:text>{{ $subFacility->name }}</flux:text>
                        <flux:badge color="{{ $subFacility->status === 'available' ? 'lime' : ($subFacility->status === 'maintenance' ? 'amber' : 'red') }}">
                            {{ ucfirst($subFacility->status) }}
                        </flux:badge>
                    </li>
                    @endforeach
                </ul>
                @else
                <flux:callout icon="information-circle">
                    <flux:callout.text>
                        Sub-facilities coming soon!
                    </flux:callout.text>
                </flux:callout>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Booking Section -->
        @if($facility->status === 'available')
        <div class="mt-8">
            @if(!$facility->has_sub_facilities)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
                <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Book This Facility</div>
                <livewire:facility-booking :facility="$facility" />
            </div>
            @else
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
                <div class="text-xs text-gray-500 dark:text-gray-400  mb-2 uppercase tracking-wider">Book Sub-facilities</div>
                <flux:callout icon="information-circle" class="mb-6">
                    <flux:callout.text>
                        This facility has sub-facilities that can be booked individually. Please select a sub-facility to book.
                    </flux:callout.text>
                </flux:callout>

                @if(isset($facility->subFacilities) && count($facility->subFacilities) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($facility->subFacilities as $subFacility)
                    <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-5 {{ $subFacility->status !== 'available' ? 'opacity-60' : '' }}">
                        <div class="flex justify-between items-center mb-3">
                            <flux:heading size="sm">{{ $subFacility->name }}</flux:heading>
                            <flux:badge color="{{ $subFacility->status === 'available' ? 'lime' : ($subFacility->status === 'maintenance' ? 'amber' : 'red') }}">
                                {{ ucfirst($subFacility->status) }}
                            </flux:badge>
                        </div>

                        @if($subFacility->status === 'available')
                        <flux:modal.trigger name="book-subfacility-{{ $subFacility->id }}">
                            <flux:button variant="primary" class="w-full mt-3">Book Now</flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="book-subfacility-{{ $subFacility->id }}" class="max-w-4xl">
                            <flux:heading size="lg" class="mb-4">Book {{ $subFacility->name }}</flux:heading>
                            <livewire:facility-booking :facility="$facility" :subFacility="$subFacility" wire:key="booking-{{ $subFacility->id }}" />
                        </flux:modal>
                        @else
                        <flux:button disabled class="w-full mt-3 opacity-50 cursor-not-allowed">Not Available</flux:button>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>
        @else
        <div class="mt-8 bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
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
        <div class="p-2">
            <flux:heading size="lg">Delete Facility?</flux:heading>
            <flux:text class="mt-4">
                <p>You're about to delete <strong>{{ $facility->name }}</strong>. This action cannot be undone.</p>
            </flux:text>
        </div>

        <div class="flex justify-end gap-3 mt-6">
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