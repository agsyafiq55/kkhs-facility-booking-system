<x-layouts.app>
    <!-- Header with breadcrumbs and back button -->
    <div class="flex justify-between items-center mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('teacher.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('teacher.facilities.index') }}">Facilities</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $facility->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button variant="filled" icon="arrow-left" href="{{ route('teacher.facilities.index') }}" wire:navigate>
            Back to Facilities
        </flux:button>
    </div>
    <livewire:teacher.facilities.show-facility :facility="$facility" />
</x-layouts.app>