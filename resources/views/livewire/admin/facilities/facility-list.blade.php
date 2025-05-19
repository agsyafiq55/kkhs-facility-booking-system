<div>
    <div class="py-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1" class="text-gray-800 dark:text-white">{{ __('Facilities Management') }}
                </flux:heading>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage and organize your campus facilities</p>
            </div>
        </div>

        <!-- Controls Section with Background -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Search Controls -->
                <div class="w-full sm:w-2/3">
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by name..." 
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-primary-500"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Add Button -->
                <div>
                    <flux:button href="{{ route('admin.facilities.create') }}" wire:navigate class="transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline-block" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Add New Facility') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <flux:callout icon="check-circle" class="mb-6">
                <flux:callout.text>{{ session('success') }}</flux:callout.text>
            </flux:callout>
        @endif

        <flux:separator variant="subtle" class="mb-6" />

        <!-- Facilities Grid -->
        @if($facilities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $facility)
                    <div class="group relative border-neutral-200 bg-zinc-50 dark:bg-zinc-900 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border dark:border-zinc-700 cursor-pointer"
                        wire:key="{{ $facility->id }}">
                        
                        <!-- Facility Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($facility->image_path)
                                <img src="{{ asset('storage/' . $facility->image_path) }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    alt="{{ $facility->name }}">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-teal-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Status badge -->
                            <div class="absolute top-3 right-3 text-xs font-medium px-2.5 py-1 rounded-full">
                                @if($facility->status === 'available')
                                    <flux:badge variant="solid" color="green">Available</flux:badge>
                                @elseif($facility->status === 'maintenance')
                                    <flux:badge variant="solid" color="amber">Maintenance</flux:badge>
                                @else
                                    <flux:badge variant="solid" color="red">Unavailable</flux:badge>
                                @endif
                            </div>
                        </div>

                        <!-- Facility Content -->
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 line-clamp-1">
                                {{ $facility->name }}
                            </h3>
                            
                            <div class="text-neutral-600 dark:text-neutral-400 text-sm mb-2">
                                <span class="font-medium">Capacity:</span> {{ $facility->capacity ?? 'Not specified' }}
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                {{ $facility->description ?? 'No description available.' }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2 mt-4">
                                <flux:button href="{{ route('admin.facilities.show', $facility) }}" wire:navigate>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    View
                                </flux:button>
                                
                                <flux:button href="{{ route('admin.facilities.edit', $facility) }}" wire:navigate>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </flux:button>
                                
                                <flux:modal.trigger name="delete-facility-{{ $facility->id }}">
                                    <flux:button variant="danger" class="text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </div>

                        <!-- Hover overlay for better UX -->
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity pointer-events-none"></div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $facilities->links() }}
            </div>
        @else
            <!-- Empty state -->
            <div class="text-center py-12 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No facilities found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first facility.</p>
                <div class="mt-4">
                    <flux:button variant="primary" href="{{ route('admin.facilities.create') }}" wire:navigate>Add New Facility</flux:button>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Delete Confirmation Modals -->
    @foreach($facilities as $facility)
        <flux:modal name="delete-facility-{{ $facility->id }}" class="md:w-96">
            <div>
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

                    <flux:button variant="danger" wire:click="deleteFacility({{ $facility->id }})" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deleteFacility">Delete Facility</span>
                        <span wire:loading wire:target="deleteFacility">Deleting...</span>
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div>