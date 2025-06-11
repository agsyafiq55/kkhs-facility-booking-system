<div>
    <form class="space-y-6">
        <!-- Basic Facility Information -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm">
            <!-- Desktop Layout: Left Column (Name, Capacity, Status) + Right Column (Image) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Faculty details -->
                <div class="space-y-4">
                    <!-- Name -->
                    <flux:field>
                        <flux:text for="name">Facility Name</flux:text>
                        <flux:input id="name" wire:model="name" required />
                        <flux:error name="name" />
                    </flux:field>

                    <!-- Capacity -->
                    <flux:field>
                        <flux:text for="capacity">Capacity</flux:text>
                        <flux:input id="capacity" wire:model="capacity" type="number" min="1" />
                        <flux:error name="capacity" />
                    </flux:field>

                    <!-- Status -->
                    <flux:field>
                        <flux:text for="status">Status</flux:text>
                        <flux:select id="status" wire:model="status">
                            <flux:select.option value="available">Available</flux:select.option>
                            <flux:select.option value="maintenance">Maintenance</flux:select.option>
                            <flux:select.option value="unavailable">Unavailable</flux:select.option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                    <!-- Opening/Closing Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:text for="opening_time">Opening Time</flux:text>
                            <flux:input id="opening_time" wire:model="opening_time" type="text" placeholder="9:00 AM" />
                            <flux:error name="opening_time" />
                            <small class="text-gray-500">Format: 9:00 AM</small>
                        </flux:field>

                        <flux:field>
                            <flux:text for="closing_time">Closing Time</flux:text>
                            <flux:input id="closing_time" wire:model="closing_time" type="text" placeholder="5:00 PM" />
                            <flux:error name="closing_time" />
                            <small class="text-gray-500">Format: 5:00 PM</small>
                        </flux:field>
                    </div>
                    
                    <!-- Booking Rule -->
                    <flux:field>
                        <flux:text for="booking_rule">Booking Rule (Days)</flux:text>
                        <flux:input id="booking_rule" wire:model="booking_rule" type="number" min="1" />
                        <flux:error name="booking_rule" />
                        <small class="text-gray-500">Minimum number of days before booking date that users can make reservations.</small>
                    </flux:field>

                    <!-- Description -->
                    <flux:field>
                        <flux:text for="description">Description</flux:text>
                        <flux:textarea id="description" wire:model="description" rows="5"></flux:textarea>
                        <flux:error name="description" />
                    </flux:field>
                </div>

                <!-- Right Column: Image upload -->
                <div>
                    <flux:field>
                        <flux:text for="image">Facility Image</flux:text>

                        <!-- Current Image -->
                        @if($facility->image_path)
                            <div class="mb-4">
                                <div class="aspect-video flex items-center justify-center rounded-md bg-zinc-900 overflow-hidden">
                                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        @endif

                        <div class="overflow-hidden">
                            <div class="pt-3">
                                <div>
                                    <input type="file" id="image" wire:model="image" class="block w-full text-sm text-neutral-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-zinc-700 file:text-zinc-300
                                        hover:file:bg-zinc-600"
                                        accept="image/png,image/jpeg,image/jpg,image/webp">
                                </div>
                                <div wire:loading wire:target="image" class="mt-2 text-sm text-zinc-500">Uploading...</div>
                                <p class="mt-2 text-xs text-zinc-600">PNG, JPG, WebP - Max 5MB</p>
                                <flux:error name="image" />
                            </div>
                        </div>
                    </flux:field>
                </div>
            </div>
        </div>

        <!-- Addons and Sub-facilities Tabs -->
        @if($facility->has_addons || $facility->has_sub_facilities)
            <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm">
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

        <!-- Submit Button -->
        <div class="flex justify-end">
            <flux:button wire:click="save" variant="primary" type="button" wire:loading.attr="disabled" class="ml-2">
                <span wire:loading.remove wire:target="save">Update Facility</span>
                <span wire:loading wire:target="save">Updating...</span>
            </flux:button>
        </div>

        @if(session('error'))
        <flux:callout icon="exclamation-triangle" class="mt-4">
            <flux:callout.heading>Error</flux:callout.heading>
            <flux:callout.text>{{ session('error') }}</flux:callout.text>
        </flux:callout>
        @endif
    </form>

    <script>
        function saveForm() {
            window.livewire.find('{{ $_instance->getId() }}').save();
        }
    </script>
</div>