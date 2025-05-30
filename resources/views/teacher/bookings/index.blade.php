<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>My Bookings</flux:heading>
            <flux:badge color="lime">Teacher</flux:badge>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-neutral-50">
                    <flux:callout icon="information-circle" class="mb-6">
                        <flux:callout.heading>Your Bookings</flux:callout.heading>
                        <flux:callout.text>
                            This page shows all the bookings you have made. You can filter and search through your bookings.
                            Pending bookings can be cancelled if needed.
                        </flux:callout.text>
                    </flux:callout>
                    
                    <livewire:teacher.bookings.booking-list />
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 