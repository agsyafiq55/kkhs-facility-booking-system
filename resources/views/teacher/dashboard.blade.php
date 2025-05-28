<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Teacher Dashboard</flux:heading>
            <flux:badge color="lime">Teacher</flux:badge>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-neutral-50">
                    <flux:callout icon="academic-cap">
                        <flux:callout.heading>Welcome, {{ Auth::user()->name }}!</flux:callout.heading>
                        <flux:callout.text>
                            This is your teacher dashboard where you can manage your room bookings and view important information.
                        </flux:callout.text>
                    </flux:callout>

                    <!-- Quick Stats Section -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        @php
                            $totalBookings = \App\Models\Booking::where('user_id', Auth::id())->count();
                            $upcomingBookings = \App\Models\Booking::where('user_id', Auth::id())
                                ->where('date', '>=', now()->format('Y-m-d'))
                                ->where('status', 'approved')
                                ->count();
                            $pendingBookings = \App\Models\Booking::where('user_id', Auth::id())
                                ->where('status', 'pending')
                                ->count();
                            $todayBookings = \App\Models\Booking::where('user_id', Auth::id())
                                ->where('date', now()->format('Y-m-d'))
                                ->count();
                        @endphp

                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-lg shadow-sm border border-blue-200 dark:border-blue-800">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Bookings</p>
                            <p class="text-2xl font-bold text-blue-800 dark:text-blue-300">{{ $totalBookings }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-lg shadow-sm border border-green-200 dark:border-green-800">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Upcoming</p>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-300">{{ $upcomingBookings }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 p-4 rounded-lg shadow-sm border border-amber-200 dark:border-amber-800">
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">Pending</p>
                            <p class="text-2xl font-bold text-amber-800 dark:text-amber-300">{{ $pendingBookings }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-lg shadow-sm border border-purple-200 dark:border-purple-800">
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Today</p>
                            <p class="text-2xl font-bold text-purple-800 dark:text-purple-300">{{ $todayBookings }}</p>
                        </div>
                    </div>

                    <!-- Today's Schedule (if any) -->
                    @php
                        $todaySchedule = \App\Models\Booking::where('user_id', Auth::id())
                            ->where('date', now()->format('Y-m-d'))
                            ->where('status', 'approved')
                            ->with(['facility', 'subFacility'])
                            ->orderBy('start_time')
                            ->limit(3)
                            ->get();
                    @endphp

                    @if($todaySchedule->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-neutral-200 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Today's Schedule
                        </h3>
                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-zinc-700">
                            <ul class="divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($todaySchedule as $booking)
                                <li class="p-4 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors duration-150">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-neutral-200">{{ $booking->facility->name }}</p>
                                            @if($booking->subFacility)
                                                <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $booking->subFacility->name }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-neutral-100">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                            </p>
                                            <flux:badge color="lime">{{ ucfirst($booking->status) }}</flux:badge>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @if($todayBookings > 3)
                                <div class="p-2 bg-gray-50 dark:bg-zinc-700/50 text-center">
                                    <flux:button variant="ghost" href="{{ route('teacher.bookings.index') }}" wire:navigate>
                                        View all {{ $todayBookings }} bookings today
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Upcoming Bookings -->
                    @php
                        $upcomingSchedule = \App\Models\Booking::where('user_id', Auth::id())
                            ->where('date', '>', now()->format('Y-m-d'))
                            ->where('status', 'approved')
                            ->with(['facility', 'subFacility'])
                            ->orderBy('date')
                            ->orderBy('start_time')
                            ->limit(5)
                            ->get()
                            ->groupBy(function($booking) {
                                return $booking->date->format('Y-m-d');
                            });
                    @endphp

                    @if(count($upcomingSchedule) > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-neutral-200 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Upcoming Bookings
                        </h3>
                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-zinc-700">
                            @foreach($upcomingSchedule as $date => $bookings)
                                <div class="border-b border-gray-200 dark:border-zinc-700 last:border-0">
                                    <div class="bg-gray-50 dark:bg-zinc-700/50 px-4 py-2">
                                        <p class="font-medium text-gray-700 dark:text-neutral-300">
                                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                        </p>
                                    </div>
                                    <ul class="divide-y divide-gray-200 dark:divide-zinc-700">
                                        @foreach($bookings as $booking)
                                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors duration-150">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-800 dark:text-neutral-200">{{ $booking->facility->name }}</p>
                                                    @if($booking->subFacility)
                                                        <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $booking->subFacility->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-neutral-100">
                                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                            @if($upcomingBookings > 5)
                                <div class="p-2 bg-gray-50 dark:bg-zinc-700/50 text-center">
                                    <flux:button variant="ghost" href="{{ route('teacher.bookings.index') }}" wire:navigate>
                                        View all {{ $upcomingBookings }} upcoming bookings
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Quick Action Cards -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-neutral-200">My Bookings</h3>
                                    <p class="text-gray-600 dark:text-neutral-400 mb-4">View and manage all your room bookings</p>
                                    <flux:button href="{{ route('teacher.bookings.index') }}" wire:navigate>
                                        View Bookings
                                    </flux:button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-neutral-200">New Booking</h3>
                                    <p class="text-gray-600 dark:text-neutral-400 mb-4">Book a room for your class or activity</p>
                                    <flux:button variant="primary" href="{{ route('teacher.facilities.index') }}" wire:navigate>
                                        Book Now
                                    </flux:button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start">
                                <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-neutral-200">Facilities</h3>
                                    <p class="text-gray-600 dark:text-neutral-400 mb-4">Browse available facilities for booking</p>
                                    <flux:button href="{{ route('teacher.facilities.index') }}" wire:navigate>View Facilities</flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 