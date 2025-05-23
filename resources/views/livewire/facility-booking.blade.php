<div>
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mt-6">
        <flux:heading size="lg" class="mb-4">Book This Facility</flux:heading>

        @if (session()->has('message'))
            <flux:callout icon="check-circle" class="mb-4">
                <flux:callout.text>
                    {{ session('message') }}
                </flux:callout.text>
            </flux:callout>
        @endif

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

        <div class="mb-4">
            <flux:field>
                <flux:label>Notes (Optional)</flux:label>
                <flux:textarea wire:model="notes" rows="3" placeholder="Add any special requirements or notes for this booking"></flux:textarea>
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
