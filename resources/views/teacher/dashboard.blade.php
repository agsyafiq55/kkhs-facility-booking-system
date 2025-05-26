<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Teacher Dashboard</flux:heading>
            <flux:badge color="lime">Teacher</flux:badge>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-neutral-50">
                    <flux:callout icon="academic-cap">
                        <flux:callout.heading>Welcome, Teacher!</flux:callout.heading>
                        <flux:callout.text>
                            This is your teacher dashboard where you can manage your room bookings.
                        </flux:callout.text>
                    </flux:callout>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">My Bookings</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">View and manage your room bookings</p>
                            <flux:button href="{{ route('teacher.bookings.index') }}" wire:navigate>
                                View Bookings
                            </flux:button>
                        </div>

                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">New Booking</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">Book a room for your class</p>
                            <flux:button variant="primary" href="{{ route('teacher.facilities.index') }}" wire:navigate>
                                Book Now
                            </flux:button>
                        </div>

                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Facilities</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">Browse available facilities for booking</p>
                            <flux:button href="{{ route('teacher.facilities.index') }}" wire:navigate>View Facilities</flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 