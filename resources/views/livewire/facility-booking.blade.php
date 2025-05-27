<div>
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mt-6">
        <div class="mb-4">
            <flux:field>
                <flux:label>Select Date</flux:label>
                <flux:input type="date" wire:model.live="selectedDate" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" />
            </flux:field>
        </div>

        <div class="mb-4">
            <flux:heading size="sm" class="mb-2">Available Time Slots</flux:heading>
            
            @if (count($availableTimeSlots) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach ($availableTimeSlots as $index => $slot)
                        <div 
                            wire:key="slot-{{ $index }}"
                            wire:click="toggleTimeSlot({{ $index }})"
                            class="border rounded-md p-2 text-center text-sm cursor-pointer transition-colors
                                {{ $slot['available'] ? 'hover:bg-neutral-100 dark:hover:bg-zinc-800' : 'opacity-50 cursor-not-allowed' }}
                                {{ in_array($slot['start'] . '-' . $slot['end'], $selectedTimeSlots) ? 'bg-sky-100 dark:bg-sky-900 border-sky-500' : '' }}"
                        >
                            {{ $slot['formatted'] }}
                            <div class="text-xs mt-1">
                                @if ($slot['available'])
                                    <span class="text-green-600 dark:text-green-400">Available</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Booked</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <flux:callout icon="information-circle">
                    <flux:callout.text>
                        No time slots available for this date.
                    </flux:callout.text>
                </flux:callout>
            @endif
        </div>

        @if ($facility->has_addons && count($availableAddons) > 0)
            <div class="mb-6">
                <flux:heading size="sm" class="mb-2">Available Add-ons</flux:heading>
                <flux:callout icon="information-circle" class="mb-4">
                    <flux:callout.text>
                        Select any additional items you need for your booking.
                    </flux:callout.text>
                </flux:callout>
                
                <div class="space-y-4">
                    @foreach ($availableAddons as $addon)
                        <div class="flex items-start p-3 rounded-lg border {{ in_array($addon['id'], $selectedAddons) ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/20' : 'border-gray-200 dark:border-zinc-700' }}">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <flux:checkbox 
                                        wire:click="toggleAddon({{ $addon['id'] }})"
                                        :checked="in_array($addon['id'], $selectedAddons)"
                                    />
                                    <span class="ml-2 font-medium">{{ $addon['name'] }}</span>
                                    
                                    @if ($addon['quantity_available'] > 0)
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ $addon['quantity_available'] }} available)</span>
                                    @endif
                                </div>
                                
                                @if ($addon['description'])
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 ml-6">
                                        {{ $addon['description'] }}
                                    </p>
                                @endif
                            </div>
                            
                            @if (in_array($addon['id'], $selectedAddons))
                                <div class="flex items-center">
                                    <button 
                                        type="button"
                                        wire:click="decrementAddonQuantity({{ $addon['id'] }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300"
                                        {{ $addonQuantities[$addon['id']] <= 1 ? 'disabled' : '' }}
                                    >
                                        <span class="text-lg">-</span>
                                    </button>
                                    
                                    <span class="mx-3 min-w-[2rem] text-center">
                                        {{ $addonQuantities[$addon['id']] }}
                                    </span>
                                    
                                    <button 
                                        type="button"
                                        wire:click="incrementAddonQuantity({{ $addon['id'] }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300"
                                        {{ $addon['quantity_available'] > 0 && $addonQuantities[$addon['id']] >= $addon['quantity_available'] ? 'disabled' : '' }}
                                    >
                                        <span class="text-lg">+</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mb-4">
            <flux:field>
                <flux:label>Purpose <span class="text-red-500">*</span></flux:label>
                <flux:textarea wire:model="purpose" rows="3" placeholder="Specify the purpose of this booking (required)"></flux:textarea>
                @error('purpose') <div class="mt-1 text-red-500 text-sm">{{ $message }}</div> @enderror
            </flux:field>
        </div>

        <div class="mt-4">
            <flux:button 
                variant="primary" 
                wire:click="bookFacility" 
                wire:loading.attr="disabled"
                @class([
                    'opacity-50 cursor-not-allowed' => count($selectedTimeSlots) === 0,
                    'opacity-100 cursor-pointer' => count($selectedTimeSlots) > 0
                ])
            >
                <span wire:loading.remove wire:target="bookFacility">Book Selected Time Slots ({{ count($selectedTimeSlots) }})</span>
                <span wire:loading wire:target="bookFacility">Processing...</span>
            </flux:button>
        </div>
    </div>
</div>
