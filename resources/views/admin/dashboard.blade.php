<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Administrator Dashboard</flux:heading>
            <flux:badge color="lime">Admin</flux:badge>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-neutral-50">
                    <flux:callout icon="user">
                        <flux:callout.heading>Welcome, Admin!</flux:callout.heading>
                        <flux:callout.text>
                            This is your admin dashboard where you can manage the entire booking system.
                        </flux:callout.text>
                    </flux:callout>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">User Management</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">Manage teachers and administrators</p>
                            <flux:button>Manage Users</flux:button>
                        </div>

                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Facility Management</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">Manage all bookable facilities</p>
                            <flux:button href="{{ route('admin.facilities.index') }}">Manage Facilities</flux:button>
                        </div>

                        <div class="bg-neutral-50 dark:bg-zinc-700 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Reports</h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-4">View booking statistics and reports</p>
                            <flux:button>View Reports</flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 