<div>
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/3">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search facilities or purpose..." 
                    class="w-full"
                />
            </div>
            
            <div class="w-full sm:w-1/3">
                <flux:select wire:model.live="status" class="w-full">
                    <flux:select.option value="">All Statuses</flux:select.option>
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="approved">Approved</flux:select.option>
                    <flux:select.option value="rejected">Rejected</flux:select.option>
                    <flux:select.option value="cancelled">Cancelled</flux:select.option>
                </flux:select>
            </div>
            
            <div class="w-full sm:w-1/3">
                <flux:input 
                    wire:model.live.debounce.500ms="dateRange" 
                    type="text" 
                    placeholder="Date range (YYYY-MM-DD to YYYY-MM-DD)" 
                    class="w-full"
                />
            </div>
        </div>
    </div>
    
    @if (session('message'))
        <flux:callout icon="check-circle" class="mb-4">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif
    
    <div class="bg-neutral-50 dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-100 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Facility</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Purpose</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-neutral-50 dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100">
                                {{ $booking->facility->name }}
                                @if ($booking->subFacility)
                                    <span class="text-gray-500 dark:text-neutral-400 block text-xs">
                                        {{ $booking->subFacility->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                {{ $booking->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'orange',
                                        'approved' => 'lime',
                                        'rejected' => 'red',
                                        'cancelled' => 'gray',
                                    ];
                                    $statusColor = $statusColors[$booking->status] ?? 'gray';
                                @endphp
                                <flux:badge color="{{ $statusColor }}">
                                    {{ ucfirst($booking->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">
                                <div class="max-w-xs truncate">
                                    {{ $booking->notes ?: 'No purpose specified' }}
                                </div>
                                <flux:modal.trigger name="view-purpose-{{ $booking->id }}">
                                    <flux:button variant="ghost" size="xs" class="mt-1">
                                        View Details
                                    </flux:button>
                                </flux:modal.trigger>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($booking->status === 'pending')
                                    <flux:button
                                        wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="Are you sure you want to cancel this booking?"
                                        variant="danger"
                                        size="sm"
                                    >
                                        Cancel
                                    </flux:button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-400">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-neutral-100 dark:bg-zinc-700">
            {{ $bookings->links() }}
        </div>
    </div>

    <!-- Purpose Detail Modals -->
    @foreach($bookings as $booking)
        <flux:modal name="view-purpose-{{ $booking->id }}" class="md:w-96">
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">Booking Details</flux:heading>
                </div>

                <div>
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Facility</div>
                            <div class="mt-1 text-gray-900 dark:text-white">
                                {{ $booking->facility->name }}
                                @if ($booking->subFacility)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->subFacility->name }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</div>
                            <div class="mt-1 text-gray-900 dark:text-white">
                                {{ $booking->date->format('F d, Y') }}<br>
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                            <div class="mt-1">
                                <flux:badge color="{{ $statusColors[$booking->status] ?? 'gray' }}">
                                    {{ ucfirst($booking->status) }}
                                </flux:badge>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Purpose</div>
                            <div class="mt-1 text-gray-900 dark:text-white">
                                {{ $booking->notes ?: 'No purpose specified' }}
                            </div>
                        </div>
                        
                        @if ($booking->addons->count() > 0)
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Add-ons</div>
                                <div class="mt-1">
                                    <ul class="list-disc pl-5 text-gray-900 dark:text-white">
                                        @foreach ($booking->addons as $addon)
                                            <li>
                                                {{ $addon->name }} 
                                                @if ($addon->pivot->quantity > 1)
                                                    <span class="text-gray-500 dark:text-gray-400">(x{{ $addon->pivot->quantity }})</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <flux:modal.close>
                        <flux:button>Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>
    @endforeach
</div> 