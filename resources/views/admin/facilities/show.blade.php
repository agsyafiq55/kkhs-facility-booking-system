<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <flux:heading>Facility Details</flux:heading>
            <flux:button href="{{ route('admin.facilities.index') }}">Back to Facilities</flux:button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-neutral-50 dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-neutral-50">
                    <livewire:admin.facilities.show-facility :facility="$facility" />
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 