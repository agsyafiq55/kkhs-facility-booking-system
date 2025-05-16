<x-layouts.app> 
    <!-- Breadcrumbs -->
    <flux:breadcrumbs class="mb-6">
            <flux:breadcrumbs.item href="{{ route('teacher.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('teacher.facilities.index') }}">Facilities</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $facility->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    <livewire:teacher.facilities.show-facility :facility="$facility" />
</x-layouts.app> 