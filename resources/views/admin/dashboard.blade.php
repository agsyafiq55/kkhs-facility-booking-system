<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Administrator Dashboard</flux:heading>
            <flux:badge color="lime">Admin</flux:badge>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Overview -->
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <flux:callout icon="user">
                        <flux:callout.heading>Welcome, {{ Auth::user()->name }}!</flux:callout.heading>
                        <flux:callout.text>
                            Here's an overview of your booking system.
                        </flux:callout.text>
                    </flux:callout>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                                    <p class="text-2xl font-semibold mt-1">{{ \App\Models\User::count() }}</p>
                                </div>
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="text-emerald-500">{{ \App\Models\User::where('role', 'teacher')->count() }}</span> Teachers, 
                                    <span class="text-amber-500">{{ \App\Models\User::where('role', 'admin')->count() }}</span> Admins
                                </p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Facilities</p>
                                    <p class="text-2xl font-semibold mt-1">{{ \App\Models\Facility::count() }}</p>
                                </div>
                                <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="text-emerald-500">{{ \App\Models\Facility::where('status', 'available')->count() }}</span> Available,
                                    <span class="text-red-500">{{ \App\Models\Facility::where('status', 'maintenance')->count() }}</span> Under Maintenance
                                </p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Bookings</p>
                                    <p class="text-2xl font-semibold mt-1">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</p>
                                </div>
                                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="text-blue-500">{{ \App\Models\Booking::where('date', '>=', now()->format('Y-m-d'))->count() }}</span> Upcoming,
                                    <span class="text-amber-500">{{ \App\Models\Booking::where('status', 'pending')->count() }}</span> Pending
                                </p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-zinc-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Bookings</p>
                                    <p class="text-2xl font-semibold mt-1">{{ \App\Models\Booking::whereDate('date', now())->count() }}</p>
                                </div>
                                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    For {{ now()->format('l, F j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-neutral-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm col-span-1">
                    <flux:heading size="lg">Quick Actions</flux:heading>
                    <div class="mt-4 space-y-3">
                        <flux:button variant="primary" href="{{ route('admin.facilities.index') }}" class="w-full">
                            Manage Facilities
                        </flux:button>
                        <flux:button variant="primary" href="{{ route('admin.bookings.index') }}" class="w-full">
                            Manage Bookings
                        </flux:button>
                        <flux:button variant="filled" class="w-full">
                            Manage Users (Coming Soon)
                        </flux:button>
                        <flux:button variant="filled" class="w-full">
                            Generate Reports (Coming Soon)
                        </flux:button>
                    </div>
                </div>
                
                <!-- Today's Bookings -->
                <div class="bg-neutral-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm col-span-1 md:col-span-3">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading size="lg">Today's Bookings</flux:heading>
                        <flux:button variant="ghost" href="{{ route('admin.bookings.index') }}">View All</flux:button>
                    </div>
                    
                    @php
                        $todayBookings = \App\Models\Booking::with(['facility', 'user'])
                            ->whereDate('date', now())
                            ->orderBy('start_time')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($todayBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Facility</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teacher</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                    @foreach($todayBookings as $booking)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $booking->facility->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                            {{ Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->user->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if($booking->status === 'confirmed')
                                                <flux:badge color="lime">Confirmed</flux:badge>
                                            @elseif($booking->status === 'pending')
                                                <flux:badge color="amber">Pending</flux:badge>
                                            @elseif($booking->status === 'cancelled')
                                                <flux:badge color="red">Cancelled</flux:badge>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2">No bookings for today</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Bookings -->
                <div class="bg-neutral-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading size="lg">Recent Bookings</flux:heading>
                    </div>
                    
                    @php
                        $recentBookings = \App\Models\Booking::with(['facility', 'user'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                                <div class="mr-4 flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300">
                                        {{ $booking->user->initials() }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">
                                        {{ $booking->facility->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $booking->user->name }} • {{ Carbon\Carbon::parse($booking->date)->format('l, M j, Y') }}
                                    </p>
                                </div>
                                <div>
                                    @if($booking->status === 'confirmed')
                                        <flux:badge color="lime">Confirmed</flux:badge>
                                    @elseif($booking->status === 'pending')
                                        <flux:badge color="amber">Pending</flux:badge>
                                    @elseif($booking->status === 'cancelled')
                                        <flux:badge color="red">Cancelled</flux:badge>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Facility Status -->
                <div class="bg-neutral-50 dark:bg-zinc-900 p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading size="lg">Facility Status</flux:heading>
                    </div>
                    
                    @php
                        $facilities = \App\Models\Facility::withCount('bookings')
                            ->orderBy('bookings_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    <div class="space-y-4">
                        @foreach($facilities as $facility)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $facility->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $facility->bookings_count }} bookings</span>
                                </div>
                                
                                {{-- Calculate percentage based on booking count --}}
                                @php
                                    $percentage = min(100, ($facility->bookings_count / 10) * 100);
                                @endphp
                                
                                {{-- Fixed progress bar implementation --}}
                                <div class="grid grid-cols-10 gap-1 mt-1 mb-2">
                                    @php
                                        // Calculate filled segments (0-10)
                                        $filledSegments = min(10, round($facility->bookings_count));
                                        
                                        // Determine color based on usage
                                        if ($filledSegments > 7) {
                                            $segmentColor = 'bg-green-600';
                                        } elseif ($filledSegments > 4) {
                                            $segmentColor = 'bg-blue-600';
                                        } elseif ($filledSegments > 2) {
                                            $segmentColor = 'bg-amber-500';
                                        } else {
                                            $segmentColor = 'bg-red-500';
                                        }
                                    @endphp
                                    
                                    @for($i = 1; $i <= 10; $i++)
                                        @if($i <= $filledSegments)
                                            <div class="{{ $segmentColor }} h-2 rounded-sm"></div>
                                        @else
                                            <div class="bg-gray-200 dark:bg-zinc-700 h-2 rounded-sm"></div>
                                        @endif
                                    @endfor
                                </div>
                                
                                <div class="mt-2 text-right">
                                    <flux:button variant="ghost" size="xs" href="{{ route('admin.facilities.edit', $facility) }}">
                                        Manage
                                    </flux:button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <flux:callout icon="information-circle">
                            <flux:callout.heading>Need maintenance?</flux:callout.heading>
                            <flux:callout.text>
                                Facilities requiring attention can be marked for maintenance in the facility management section.
                                <flux:callout.link href="{{ route('admin.facilities.index') }}">Manage Facilities</flux:callout.link>
                            </flux:callout.text>
                        </flux:callout>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 