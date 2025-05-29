<div>
    <div class="py-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1" class="text-gray-800 dark:text-white">{{ __('Bookings Management') }}</flux:heading>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">View and manage all facility bookings</p>
            </div>
            
            <!-- Stats Overview -->
            <div class="flex flex-wrap gap-4">
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 min-w-32 text-center shadow-sm">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending</div>
                    <div class="text-2xl font-bold text-amber-500 mt-1">{{ $pendingCount ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 min-w-32 text-center shadow-sm">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Today</div>
                    <div class="text-2xl font-bold text-blue-500 mt-1">{{ $todayCount ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 min-w-32 text-center shadow-sm">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">This Week</div>
                    <div class="text-2xl font-bold text-indigo-500 mt-1">{{ $weekCount ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Controls Section with Background -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Status Filter -->
                <div>
                    <flux:text class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Filter by status:</flux:text>
                    <div class="flex flex-wrap gap-2">
                        <flux:button
                            wire:click="filterByStatus('')"
                            variant="{{ !$status ? 'primary' : 'filled' }}"
                            class="text-sm">
                            All
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('pending')"
                            variant="{{ $status === 'pending' ? 'primary' : 'filled' }}"
                            class="text-sm">
                            Pending
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('approved')"
                            variant="{{ $status === 'approved' ? 'primary' : 'filled' }}"
                            class="text-sm">
                            Approved
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('rejected')"
                            variant="{{ $status === 'rejected' ? 'primary' : 'filled' }}"
                            class="text-sm">
                            Rejected
                        </flux:button>
                        <flux:button
                            wire:click="filterByStatus('cancelled')"
                            variant="{{ $status === 'cancelled' ? 'primary' : 'filled' }}"
                            class="text-sm">
                            Cancelled
                        </flux:button>
                    </div>
                </div>

                <!-- Search Filter -->
                <div>
                    <flux:field>
                        <flux:label>Search bookings</flux:label>
                        <flux:input wire:model.debounce.300ms="search" placeholder="Search by user, facility or purpose..." />
                    </flux:field>
                </div>
                
                <!-- Date Range Filter -->
                <div>
                    <flux:text class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Filter by date range:</flux:text>
                    <flux:select wire:model.live="dateRange" wire:change="filterByDateRange($event.target.value)" class="w-full">
                        <flux:select.option value="all">All dates</flux:select.option>
                        <flux:select.option value="day">Today</flux:select.option>
                        <flux:select.option value="week">This week</flux:select.option>
                        <flux:select.option value="month">This month</flux:select.option>
                    </flux:select>
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

        <!-- Pending Bookings Table -->
        @if($hasPendingBookings)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="lg" level="2" class="flex items-center">
                    <span class="text-amber-500 mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Pending Bookings ({{ $pendingCount }})
                </flux:heading>
            </div>
            
            <div class="bg-amber-50 dark:bg-amber-900/20 overflow-hidden shadow-sm rounded-lg border border-amber-200 dark:border-amber-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200 dark:divide-amber-800">
                        <thead class="bg-amber-100 dark:bg-amber-900/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">Facility</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-amber-200 dark:divide-amber-800">
                            @foreach ($pendingBookings as $booking)
                            <tr class="hover:bg-amber-50 dark:hover:bg-amber-900/10 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->facility->name }}</div>
                                    @if($booking->subFacility)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->subFacility->name }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $booking->date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->notes ?: 'No purpose specified' }}
                                    </div>
                                    <flux:modal.trigger name="admin-view-purpose-pending-{{ $booking->id }}">
                                        <flux:button variant="filled" size="xs" class="mt-1">
                                            View Details
                                        </flux:button>
                                    </flux:modal.trigger>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <flux:tooltip content="Approve">
                                            <flux:button wire:click="approveBooking({{ $booking->id }})" variant="primary" class="p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </flux:button>
                                        </flux:tooltip>
                                        
                                        <flux:tooltip content="Reject">
                                            <flux:button wire:click="rejectBooking({{ $booking->id }})" variant="danger" class="p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </flux:button>
                                        </flux:tooltip>
                                        
                                        <flux:tooltip content="Edit">
                                            <flux:button href="{{ route('admin.bookings.edit', $booking) }}" wire:navigate variant="ghost">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </flux:button>
                                        </flux:tooltip>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- All Bookings Table -->
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg" level="2">
                @if($dateRange == 'day')
                    Bookings for today
                @elseif($dateRange == 'week')
                    Bookings for this week
                @elseif($dateRange == 'month')
                    Bookings in {{ now()->format('F') }}
                @else
                    All Bookings
                @endif
                
                @if($status)
                    <span class="text-base font-normal ml-2 text-gray-500">
                        ({{ ucfirst($status) }})
                    </span>
                @endif
            </flux:heading>
            
            <div>
                <flux:button icon="arrow-up-tray" wire:click="exportBookings">
                    Export as CSV
                </flux:button>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Purpose</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->facility->name }}</div>
                                @if($booking->subFacility)
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->subFacility->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $booking->date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @switch($booking->status)
                                @case('pending')
                                <div class="flex flex-col items-center">
                                    <flux:badge color="amber">Pending</flux:badge>
                                    
                                    <div class="flex mt-2 space-x-1">
                                        <flux:tooltip content="Approve">
                                            <flux:button wire:click="approveBooking({{ $booking->id }})" variant="filled" class="p-1 text-green-600 hover:text-green-800 dark:text-green-500 dark:hover:text-green-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </flux:button>
                                        </flux:tooltip>
                                        
                                        <flux:tooltip content="Reject">
                                            <flux:button wire:click="rejectBooking({{ $booking->id }})" variant="filled" class="p-1 text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </flux:button>
                                        </flux:tooltip>
                                    </div>
                                </div>
                                @break
                                @case('approved')
                                <div class="flex justify-center">
                                    <flux:badge color="green">Approved</flux:badge>
                                </div>
                                @break
                                @case('rejected')
                                <div class="flex justify-center">
                                    <flux:badge color="red">Rejected</flux:badge>
                                </div>
                                @break
                                @case('cancelled')
                                <div class="flex justify-center">
                                    <flux:badge color="gray">Cancelled</flux:badge>
                                </div>
                                @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->notes ?: 'No purpose specified' }}
                                </div>
                                <flux:modal.trigger name="admin-view-purpose-{{ $booking->id }}">
                                    <flux:button variant="filled" size="xs" class="mt-1">
                                        View Details
                                    </flux:button>
                                </flux:modal.trigger>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <flux:tooltip content="Edit">
                                        <flux:button href="{{ route('admin.bookings.edit', $booking) }}" wire:navigate variant="ghost">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </flux:button>
                                    </flux:tooltip>

                                    <flux:tooltip content="Delete">
                                        <flux:modal.trigger name="delete-booking-{{ $booking->id }}">
                                            <flux:button variant="ghost" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </flux:button>
                                        </flux:modal.trigger>
                                    </flux:tooltip>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
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
    <flux:modal name="admin-view-purpose-{{ $booking->id }}" class="md:w-[500px]">
        <div class="space-y-6">
            <!-- Header with Status Badge -->
            <div class="border-b border-gray-200 dark:border-zinc-700 pb-4">
                <flux:heading size="lg">Booking Details</flux:heading>
                <div class="mt-2">
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

            <!-- Booking ID and Date -->
            <div class="bg-gray-50 dark:bg-zinc-800 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Booking ID</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $booking->id }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Created On</div>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $booking->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Booking Information -->
            <div class="space-y-4">
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Facility</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->facility->name }}</div>
                    @if ($booking->subFacility)
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $booking->subFacility->name }}</div>
                    @endif
                </div>
                
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Date & Time</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->date->format('F d, Y') }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Booked By</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->user->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $booking->user->email }}</div>
                </div>
                
                @if ($booking->addons->count() > 0)
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Add-ons</div>
                    <ul class="text-sm text-gray-900 dark:text-white space-y-1">
                        @foreach ($booking->addons as $addon)
                        <li class="flex justify-between">
                            <span>{{ $addon->name }}</span>
                            @if ($addon->pivot->quantity > 1)
                            <span class="text-gray-500 dark:text-gray-400">x{{ $addon->pivot->quantity }}</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            
            <!-- Purpose/Notes Section -->
            <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Purpose</div>
                <div class="text-sm">
                    {{ $booking->notes ?: 'No purpose specified' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-zinc-700">
                <div>
                    @if($booking->status === 'pending')
                    <div class="flex gap-2">
                        <flux:button wire:click="approveBooking({{ $booking->id }})" variant="primary">
                            Approve Booking
                        </flux:button>
                        
                        <flux:button wire:click="rejectBooking({{ $booking->id }})" variant="danger">
                            Reject
                        </flux:button>
                    </div>
                    @elseif($booking->status === 'approved')
                    <flux:button wire:click="rejectBooking({{ $booking->id }})" variant="danger">
                        Cancel Booking
                    </flux:button>
                    @elseif($booking->status === 'rejected')
                    <flux:button wire:click="approveBooking({{ $booking->id }})" variant="primary">
                        Approve Instead
                    </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </flux:modal>
    @endforeach

    <!-- Pending Bookings Modals -->
    @foreach($pendingBookings as $booking)
    <!-- Purpose Detail Modal for Pending Bookings -->
    <flux:modal name="admin-view-purpose-pending-{{ $booking->id }}" class="md:w-[500px]">
        <div class="space-y-6">
            <!-- Header with Status Badge -->
            <div class="border-b border-amber-200 dark:border-amber-800 pb-4">
                <flux:heading size="lg">Pending Booking Details</flux:heading>
                <div class="mt-2">
                    <flux:badge color="amber">Pending</flux:badge>
                </div>
            </div>

            <!-- Booking ID and Date -->
            <div class="bg-amber-50 dark:bg-amber-900/10 p-3 rounded-lg border border-amber-200 dark:border-amber-800">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-amber-700 dark:text-amber-400 uppercase font-medium">Booking ID</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $booking->id }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-amber-700 dark:text-amber-400 uppercase font-medium">Created On</div>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $booking->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Booking Information -->
            <div class="space-y-4">
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Facility</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->facility->name }}</div>
                    @if ($booking->subFacility)
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $booking->subFacility->name }}</div>
                    @endif
                </div>
                
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Date & Time</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->date->format('F d, Y') }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                    </div>
                </div>
                
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Booked By</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->user->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $booking->user->email }}</div>
                </div>
                
                @if ($booking->addons->count() > 0)
                <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Add-ons</div>
                    <ul class="text-sm text-gray-900 dark:text-white space-y-1">
                        @foreach ($booking->addons as $addon)
                        <li class="flex justify-between">
                            <span>{{ $addon->name }}</span>
                            @if ($addon->pivot->quantity > 1)
                            <span class="text-gray-500 dark:text-gray-400">x{{ $addon->pivot->quantity }}</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            
            <!-- Purpose/Notes Section -->
            <div class="bg-white dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-medium mb-1">Purpose</div>
                <div class="text-sm">
                    {{ $booking->notes ?: 'No purpose specified' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-4 border-t border-amber-200 dark:border-amber-800">
                <div class="flex gap-2 w-full">
                    <flux:button wire:click="approveBooking({{ $booking->id }})" variant="primary" class="flex-1">
                        Approve Booking
                    </flux:button>
                    
                    <flux:button wire:click="rejectBooking({{ $booking->id }})" variant="danger" class="flex-1">
                        Reject
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>
    @endforeach
</div>