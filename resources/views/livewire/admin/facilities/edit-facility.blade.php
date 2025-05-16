<div>
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <flux:field>
                <flux:label for="name">Facility Name</flux:label>
                <flux:input id="name" wire:model="name" required />
                <flux:error name="name" />
            </flux:field>

            <!-- Capacity -->
            <flux:field>
                <flux:label for="capacity">Capacity</flux:label>
                <flux:input id="capacity" wire:model="capacity" type="number" min="1" />
                <flux:error name="capacity" />
            </flux:field>

            <!-- Status -->
            <flux:field>
                <flux:label for="status">Status</flux:label>
                <flux:select id="status" wire:model="status">
                    <flux:select.option value="available">Available</flux:select.option>
                    <flux:select.option value="maintenance">Maintenance</flux:select.option>
                    <flux:select.option value="unavailable">Unavailable</flux:select.option>
                </flux:select>
                <flux:error name="status" />
            </flux:field>
        </div>

        <!-- Description -->
        <flux:field>
            <flux:label for="description">Description</flux:label>
            <flux:textarea id="description" wire:model="description" rows="4"></flux:textarea>
            <flux:error name="description" />
        </flux:field>

        <!-- Features -->
        <flux:field>
            <flux:label>Features</flux:label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                @foreach($availableFeatures as $value => $label)
                    <flux:field variant="inline">
                        <flux:checkbox id="features_{{ $value }}" wire:model.live="features" value="{{ $value }}" />
                        <flux:label for="features_{{ $value }}">{{ $label }}</flux:label>
                    </flux:field>
                @endforeach
            </div>
            <flux:error name="features" />
        </flux:field>

        <!-- Current Image -->
        @if($facility->image_path)
            <div class="mb-4">
                <flux:label>Current Image</flux:label>
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="h-32 w-auto rounded-md">
                </div>
            </div>
        @endif

        <!-- Image -->
        <flux:field>
            <flux:label for="image">Update Image</flux:label>
            <input type="file" id="image" wire:model="image" class="block w-full text-sm text-neutral-500 dark:text-neutral-400 
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-neutral-50 file:text-neutral-700
                dark:file:bg-zinc-700 dark:file:text-neutral-100
                hover:file:bg-neutral-100 dark:hover:file:bg-zinc-600">
            <div wire:loading wire:target="image" class="mt-2 text-sm text-neutral-500">Uploading...</div>
            <flux:error name="image" />
        </flux:field>

        <div class="flex justify-end">
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Update Facility</span>
                <span wire:loading wire:target="save">Updating...</span>
            </flux:button>
        </div>
    </form>
</div>