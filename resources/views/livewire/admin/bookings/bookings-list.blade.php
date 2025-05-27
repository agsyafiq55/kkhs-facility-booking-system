<div>
    <div class="py-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1" class="text-gray-800 dark:text-white">{{ __('Bookings Management') }}</flux:heading>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">View and manage all facility bookings</p>
            </div>
        </div>

        <!-- Controls Section with Background -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Filter Controls -->
                <div class="w-full sm:w-1/2">
                    <flux:text class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Filter by status:</flux:text>
                    <div class="flex flex-wrap gap-2">
                        <flux:button
                            wire:click="filterByStatus('')"
                            variant="{{ !$status ? 'primary' : 'subtle' }}"
                            class="text-sm">
                            All
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('pending')"
                            variant="{{ $status === 'pending' ? 'filled' : 'subtle' }}"
                            class="text-sm">
                            Pending
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('approved')"
                            variant="{{ $status === 'approved' ? 'filled' : 'subtle' }}"
                            class="text-sm">
                            Approved
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('rejected')"
                            variant="{{ $status === 'rejected' ? 'filled' : 'subtle' }}"
                            class="text-sm">
                            Rejected
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('cancelled')"
                            variant="{{ $status === 'cancelled' ? 'filled' : 'subtle' }}"
                            class="text-sm">
                            Cancelled
                        </flux:button>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="w-full sm:w-1/2">
                    <flux:text class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Filter by date range:</flux:text>
                    <div class="flex items-center">
                        <flux:select wire:model.live="dateRange" wire:change="filterByDateRange($event.target.value)" class="w-full">
                            <flux:select.option value="all">All dates</flux:select.option>
                            <flux:select.option value="day">Today</flux:select.option>
                            <flux:select.option value="week">This week</flux:select.option>
                            <flux:select.option value="month">This month</flux:select.option>
                        </flux:select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <flux:callout icon="check-circle" class="mb-6">
            <flux:callout.heading>Success</flux:callout.heading>
            <flux:callout.text>{{ session('success') }}</flux:callout.text>
        </flux:callout>
        @endif

        <flux:separator variant="subtle" class="mb-6" />

        <!-- Bookings Table -->
        <div class="flex-1 max-md:p-6 self-stretch">
            <flux:heading size="xl" level="1">
                @if($dateRange == 'day')
                    Bookings for today
                @elseif($dateRange == 'week')
                    Bookings for this week
                @elseif($dateRange == 'month')
                    Bookings in {{ now()->format('F') }}
                @else
                    All Bookings
                @endif
            </flux:heading>
        </div>
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sub-Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Purpose</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $booking->facility->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $booking->subFacility ? $booking->subFacility->name : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $booking->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($booking->status)
                                @case('pending')
                                <flux:badge color="amber">Pending</flux:badge>
                                @break
                                @case('approved')
                                <flux:badge color="green">Approved</flux:badge>
                                @break
                                @case('rejected')
                                <flux:badge color="red">Rejected</flux:badge>
                                @break
                                @case('cancelled')
                                <flux:badge color="gray">Cancelled</flux:badge>
                                @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="max-w-xs truncate">
                                    {{ $booking->notes ?: 'No purpose specified' }}
                                </div>
                                <flux:modal.trigger name="admin-view-purpose-{{ $booking->id }}">
                                    <flux:button variant="ghost" size="xs" class="mt-1">
                                        View Details
                                    </flux:button>
                                </flux:modal.trigger>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <flux:button href="{{ route('admin.bookings.edit', $booking) }}" wire:navigate>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit
                                    </flux:button>

                                    <flux:modal.trigger name="delete-booking-{{ $booking->id }}">
                                        <flux:button variant="danger" class="text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Delete
                                        </flux:button>
                                    </flux:modal.trigger>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-lg font-medium">No bookings found</p>
                                <p class="text-sm">Try adjusting your filters or check back later</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modals -->
    @foreach($bookings as $booking)
    <flux:modal name="delete-booking-{{ $booking->id }}" class="md:w-96">
        <div>
            <div>
                <flux:heading size="lg">Delete Booking?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete booking #{{ $booking->id }} for <strong>{{ $booking->facility->name }}</strong>.</p>
                    <p>This action cannot be undone.</p>
                </flux:text>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger" type="submit">
                        Delete Booking
                    </flux:button>
                </form>
            </div>
        </div>
    </flux:modal>

    <!-- Purpose Detail Modal -->
    <flux:modal name="admin-view-purpose-{{ $booking->id }}" class="md:w-96">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Booking Purpose</flux:heading>
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
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Booked by</div>
                        <div class="mt-1 text-gray-900 dark:text-white">
                            {{ $booking->user->name }}
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
                            @switch($booking->status)
                            @case('pending')
                            <flux:badge color="amber">Pending</flux:badge>
                            @break
                            @case('approved')
                            <flux:badge color="green">Approved</flux:badge>
                            @break
                            @case('rejected')
                            <flux:badge color="red">Rejected</flux:badge>
                            @break
                            @case('cancelled')
                            <flux:badge color="gray">Cancelled</flux:badge>
                            @break
                            @endswitch
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