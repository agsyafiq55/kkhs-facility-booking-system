<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Teacher Dashboard</flux:heading>
            <flux:badge color="lime">Teacher</flux:badge>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <flux:callout icon="academic-cap">
                        <flux:callout.heading>Welcome, Teacher!</flux:callout.heading>
                        <flux:callout.text>
                            This is your teacher dashboard where you can manage your room bookings.
                        </flux:callout.text>
                    </flux:callout>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">My Bookings</h3>
                            <p class="text-gray-600 mb-4">View and manage your room bookings</p>
                            <flux:button>View Bookings</flux:button>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">New Booking</h3>
                            <p class="text-gray-600 mb-4">Book a room for your class</p>
                            <flux:button variant="primary">Book Now</flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 