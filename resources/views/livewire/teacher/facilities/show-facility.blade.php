<div>
    <div class="bg-neutral-100 dark:bg-zinc-800 rounded-lg p-6">
        <!-- Back Button -->
        <div class="mt-6">
            <flux:button variant="ghost" href="{{ route('teacher.facilities.index') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Facilities
            </flux:button>
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
                <div class="flex justify-between items-start">
                    <flux:heading size="xl" class="font-semibold">{{ $facility->name }}</flux:heading>
                    <div>
                        @if($facility->status === 'available')
                            <flux:badge color="lime">Available</flux:badge>
                        @elseif($facility->status === 'maintenance')
                            <flux:badge color="amber">Maintenance</flux:badge>
                        @else
                            <flux:badge color="red">Unavailable</flux:badge>
                        @endif
                    </div>
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
            </div>
        </div>

        <!-- Description section -->
        <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-3">
            <flux:heading size="lg" class="font-semibold">Description</flux:heading>
            <div class="mt-2 text-justify prose max-w-none dark:prose-invert prose-a:text-primary-600 dark:prose-a:text-primary-400">
                @if($facility->description)
                    @if(is_array($facility->description))
                        <p>{{ implode(' ', $facility->description) }}</p>
                    @else
                        <p>{{ $facility->description }}</p>
                    @endif
                @else
                    <p class="text-gray-500 dark:text-gray-400">No description available for this facility.</p>
                @endif
            </div>
        </div>
        
        <!-- Booking Section -->
        @if($facility->status === 'available')
            @if(!$facility->has_sub_facilities)
                <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                    <flux:heading size="lg">Book Facility</flux:heading>
                    <livewire:facility-booking :facility="$facility" />
                </div>
            @else
                <div class="mt-6 bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                    <flux:heading size="lg">Book Sub-facilities</flux:heading>
                    
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
</div> 