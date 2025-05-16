<div>
    <div class="py-6">
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Facility Image -->
                <div class="w-full lg:w-1/2 h-80 rounded-xl overflow-hidden border border-neutral-200 dark:border-zinc-700">
                    @if($facility->image_path)
                        <img src="{{ asset('storage/' . $facility->image_path) }}" 
                             class="w-full h-full object-cover" 
                             alt="{{ $facility->name }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-blue-500 to-teal-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Facility Details -->
                <div class="w-full lg:w-1/2">
                    <div class="flex justify-between items-start">
                        <flux:heading size="xl">{{ $facility->name }}</flux:heading>
                        <div>
                            @if($facility->status === 'available')
                                <flux:badge variant="solid" color="green">Available</flux:badge>
                            @elseif($facility->status === 'maintenance')
                                <flux:badge variant="solid" color="amber">Maintenance</flux:badge>
                            @else
                                <flux:badge variant="solid" color="red">Unavailable</flux:badge>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <!-- Capacity -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-primary-600 dark:text-primary-400 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-neutral-900 dark:text-neutral-100">Capacity</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">{{ $facility->capacity ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Equipment/Features (if any) -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-primary-600 dark:text-primary-400 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-neutral-900 dark:text-neutral-100">Features</h3>
                                <p class="text-neutral-600 dark:text-neutral-400">
                                    @if(isset($facility->features))
                                        @if(is_array($facility->features))
                                            {{ implode(', ', $facility->features) }}
                                        @else
                                            {{ $facility->features }}
                                        @endif
                                    @else
                                        No specific features listed
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Button -->
                    @if($facility->status === 'available')
                        <div class="mt-8">
                            <flux:button variant="primary">
                                Request Booking
                                <span class="text-xs ml-1">(Coming Soon)</span>
                            </flux:button>
                        </div>
                    @elseif($facility->status === 'maintenance')
                        <flux:callout icon="information-circle" class="mt-8">
                            <flux:callout.text>This facility is currently under maintenance and not available for booking.</flux:callout.text>
                        </flux:callout>
                    @else
                        <flux:callout icon="x-circle" class="mt-8">
                            <flux:callout.text>This facility is currently unavailable for booking.</flux:callout.text>
                        </flux:callout>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mt-8 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-neutral-200 dark:border-zinc-700 p-6">
            <flux:heading size="lg" class="mb-4">Description</flux:heading>
            <div class="prose max-w-none dark:prose-invert prose-a:text-primary-600 dark:prose-a:text-primary-400">
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

        <!-- Guidelines -->
        <div class="mt-8 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-neutral-200 dark:border-zinc-700 p-6">
            <flux:heading size="lg" class="mb-4">Usage Guidelines</flux:heading>
            <div class="prose max-w-none dark:prose-invert prose-a:text-primary-600 dark:prose-a:text-primary-400">
                <ul class="space-y-2">
                    <li>Please ensure the facility is left clean after use</li>
                    <li>Report any damages or issues immediately</li>
                    <li>Adhere to the scheduled booking times</li>
                    <li>Follow all safety procedures and protocols</li>
                    <li>Maximum capacity must not be exceeded</li>
                </ul>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <flux:button variant="ghost" href="{{ route('teacher.facilities.index') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Facilities
            </flux:button>
        </div>
    </div>
</div> 