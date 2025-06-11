<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Basic Facility Information -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm">
            <!-- Desktop Layout: Left Column (Name, Capacity, Status) + Right Column (Image) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Faculty details -->
                <div class="space-y-4">
                    <!-- Name -->
                    <flux:field>
                        <flux:text for="name">Facility Name</flux:label>
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
                        <div class="overflow-hidden">
                            <div class="aspect-video flex flex-col items-center justify-center rounded-md bg-zinc-900">
                                @if($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Facility image preview" class="w-full h-full object-cover">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-zinc-500">No image selected</p>
                                @endif
                            </div>
                            
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

            <!-- Facility Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <flux:fieldset>
                    <flux:legend>Extra Options</flux:legend>

                    <div class="space-y-4">
                        <flux:switch id="has_addons" wire:model.live="has_addons" label="Add-Ons" description="This facility has add-ons (chairs, tables, etc.)" />

                        <flux:separator variant="subtle" />

                        <flux:switch id="has_sub_facilities" wire:model.live="has_sub_facilities" label="Sub-facilities" description="This facility has sub-facilities (multiple courts, rooms, etc.)" />
                    </div>
                </flux:fieldset>
            </div>
        </div>

        <!-- Add-ons Section -->
        @if($has_addons)
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm">
            <flux:heading size="lg" class="mb-4">Facility Add-ons</flux:heading>

            <flux:callout icon="information-circle" class="mb-4">
                <flux:callout.heading>Add-ons Setup</flux:callout.heading>
                <flux:callout.text>
                    Add-ons are additional items that can be included when booking this facility,
                    such as chairs, tables, projectors, etc.
                </flux:callout.text>
            </flux:callout>

            <!-- Add-on Form -->
            <div class="bg-neutral-50 dark:bg-zinc-700/50 p-4 rounded-lg mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="addonName">Add-on Name</flux:label>
                        <flux:input id="addonName" wire:model="addonName" placeholder="e.g., Chair" />
                        <flux:error name="addonName" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="addonQuantity">Quantity Available (0 = Unlimited)</flux:label>
                        <flux:input id="addonQuantity" wire:model="addonQuantity" type="number" min="0" />
                        <flux:error name="addonQuantity" />
                    </flux:field>
                </div>

                <div class="mt-4">
                    <flux:field>
                        <flux:label for="addonDescription">Description</flux:label>
                        <flux:textarea id="addonDescription" wire:model="addonDescription" rows="2"></flux:textarea>
                        <flux:error name="addonDescription" />
                    </flux:field>

                    <flux:field variant="inline" class="mt-2">
                        <flux:label for="addonIsAvailable">Available</flux:label>
                        <flux:switch id="addonIsAvailable" wire:model="addonIsAvailable" />
                        <flux:error name="addonIsAvailable" />
                    </flux:field>
                </div>

                <div class="flex justify-end mt-4">
                    @if($editingAddonIndex !== null)
                    <div class="flex space-x-2">
                        <flux:button wire:click="resetAddonForm" variant="subtle">Cancel</flux:button>
                        <flux:button wire:click="addAddon" variant="primary">Update Add-on</flux:button>
                    </div>
                    @else
                    <flux:button wire:click="addAddon" variant="primary">Add to List</flux:button>
                    @endif
                </div>
            </div>

            <!-- Add-ons List -->
            @if(count($addons) > 0)
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($addons as $index => $addon)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $addon['name'] }}</div>
                                @if(!empty($addon['description']))
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($addon['description'], 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $addon['quantity_available'] == 0 ? 'Unlimited' : $addon['quantity_available'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($addon['is_available'])
                                <flux:badge color="lime">Yes</flux:badge>
                                @else
                                <flux:badge color="rose">No</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <flux:button variant="subtle" wire:click="editAddon({{ $index }})">Edit</flux:button>
                                    <flux:button variant="danger" wire:click="removeAddon({{ $index }})">Remove</flux:button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                No add-ons added yet. Add one using the form above.
            </div>
            @endif
        </div>
        @endif

        <!-- Sub-facilities Section -->
        @if($has_sub_facilities)
        <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-sm">
            <flux:heading size="lg" class="mb-4">Sub-facilities</flux:heading>

            <flux:callout icon="information-circle" class="mb-4">
                <flux:callout.heading>Sub-facilities Setup</flux:callout.heading>
                <flux:callout.text>
                    Sub-facilities are separate bookable spaces within this facility. For example, if this facility is a sports hall,
                    sub-facilities could be individual badminton courts.
                </flux:callout.text>
            </flux:callout>

            <!-- Sub-facility Form -->
            <div class="bg-neutral-50 dark:bg-zinc-700/50 p-4 rounded-lg mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label for="subFacilityName">Name</flux:label>
                        <flux:input id="subFacilityName" wire:model="subFacilityName" placeholder="e.g., Court 1" />
                        <flux:error name="subFacilityName" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="subFacilityCapacity">Capacity</flux:label>
                        <flux:input id="subFacilityCapacity" wire:model="subFacilityCapacity" type="number" min="1" />
                        <flux:error name="subFacilityCapacity" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <flux:field>
                        <flux:label for="subFacilityStatus">Status</flux:label>
                        <flux:select id="subFacilityStatus" wire:model="subFacilityStatus">
                            <flux:select.option value="available">Available</flux:select.option>
                            <flux:select.option value="maintenance">Maintenance</flux:select.option>
                            <flux:select.option value="unavailable">Unavailable</flux:select.option>
                        </flux:select>
                        <flux:error name="subFacilityStatus" />
                    </flux:field>

                    <flux:field variant="inline">
                        <flux:label for="subFacilityIsBookable">Bookable</flux:label>
                        <flux:switch id="subFacilityIsBookable" wire:model="subFacilityIsBookable" />
                        <flux:error name="subFacilityIsBookable" />
                    </flux:field>
                </div>

                <flux:field class="mt-4">
                    <flux:label for="subFacilityDescription">Description</flux:label>
                    <flux:textarea id="subFacilityDescription" wire:model="subFacilityDescription" rows="2"></flux:textarea>
                    <flux:error name="subFacilityDescription" />
                </flux:field>

                <flux:field class="mt-4">
                    <flux:label for="subFacilityImage">Image</flux:label>
                    <input type="file" id="subFacilityImage" wire:model="subFacilityImage" class="block w-full text-sm text-neutral-500 dark:text-neutral-400 
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-neutral-50 file:text-neutral-700
                            dark:file:bg-zinc-700 dark:file:text-neutral-100
                            hover:file:bg-neutral-100 dark:hover:file:bg-zinc-600">
                    <div wire:loading wire:target="subFacilityImage" class="mt-2 text-sm text-neutral-500">Uploading...</div>
                    <flux:error name="subFacilityImage" />
                </flux:field>

                <div class="flex justify-end mt-4">
                    @if($editingSubFacilityIndex !== null)
                    <div class="flex space-x-2">
                        <flux:button wire:click="resetSubFacilityForm" variant="subtle">Cancel</flux:button>
                        <flux:button wire:click="addSubFacility" variant="primary">Update Sub-facility</flux:button>
                    </div>
                    @else
                    <flux:button wire:click="addSubFacility" variant="primary">Add to List</flux:button>
                    @endif
                </div>
            </div>

            <!-- Sub-facilities List -->
            @if(count($subFacilities) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach($subFacilities as $index => $subFacility)
                <div class="bg-white dark:bg-zinc-700 rounded-lg shadow overflow-hidden">
                    <div class="h-32 bg-neutral-100 dark:bg-zinc-600 flex items-center justify-center">
                        @if(!empty($subFacility['image_path']))
                        <img src="{{ Storage::url('temp/' . $subFacility['image_path']) }}"
                            alt="{{ $subFacility['name'] }}"
                            class="w-full h-full object-cover">
                        @else
                        <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $subFacility['name'] }}</h3>
                        <div class="flex items-center justify-between mt-2">
                            <flux:badge color="{{ $subFacility['status'] === 'available' ? 'lime' : ($subFacility['status'] === 'maintenance' ? 'amber' : 'rose') }}">
                                {{ ucfirst($subFacility['status']) }}
                            </flux:badge>
                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                Capacity: {{ $subFacility['capacity'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <flux:button variant="subtle" wire:click="editSubFacility({{ $index }})" size="sm">Edit</flux:button>
                            <flux:button variant="danger" wire:click="removeSubFacility({{ $index }})" size="sm">Remove</flux:button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                No sub-facilities added yet. Add one using the form above.
            </div>
            @endif
        </div>
        @endif

        <!-- Submit Button -->
        <div class="flex justify-end">
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Create Facility</span>
                <span wire:loading wire:target="save">Creating...</span>
            </flux:button>
        </div>

        @if(session('error'))
        <flux:callout icon="exclamation-triangle" class="mt-4">
            <flux:callout.heading>Error</flux:callout.heading>
            <flux:callout.text>{{ session('error') }}</flux:callout.text>
        </flux:callout>
        @endif
    </form>
</div>