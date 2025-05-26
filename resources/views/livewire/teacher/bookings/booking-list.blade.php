<div>
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/3">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search facilities or notes..." 
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Notes</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-neutral-50 dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($bookings as $booking)
                        <tr>
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
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400 max-w-xs truncate">
                                {{ $booking->notes ?: 'No notes' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($booking->status === 'pending')
                                    <flux:button
                                        wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="Are you sure you want to cancel this booking?"
                                        variant="subtle"
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
</div> 