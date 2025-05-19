<div>
    <div class="flex justify-between items-center mb-4">
        <flux:heading>Manage Add-ons for {{ $facility->name }}</flux:heading>
        <flux:button wire:click="addAddon">Add New Add-on</flux:button>
    </div>

    @if ($facility->addons->isEmpty())
        <flux:callout icon="information-circle">
            <flux:callout.heading>No Add-ons Available</flux:callout.heading>
            <flux:callout.text>
                This facility doesn't have any add-ons yet. Add-ons are extra items like chairs, tables, 
                projectors, etc. that can be included when booking this facility.
            </flux:callout.text>
        </flux:callout>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Availability</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($addons as $addon)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $addon->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($addon->description, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if ($addon->is_available)
                                    <flux:badge color="lime">Available</flux:badge>
                                @else
                                    <flux:badge color="rose">Unavailable</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $addon->quantity_available == 0 ? 'Unlimited' : $addon->quantity_available }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <flux:button variant="subtle" wire:click="editAddon({{ $addon->id }})">Edit</flux:button>
                                    <flux:button variant="danger" wire:click="$dispatch('confirm-delete', { id: {{ $addon->id }}, type: 'addon', action: 'delete-addon' })">Delete</flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <div class="mt-4">
            {{ $addons->links() }}
        </div>
    @endif

    <!-- Modal for Add/Edit Add-on -->
    <flux:modal wire:model="isModalOpen" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $editing ? 'Edit Add-on' : 'Add New Add-on' }}</flux:heading>
            
            <flux:field>
                <flux:label for="name">Name</flux:label>
                <flux:input id="name" wire:model="name" required />
                <flux:error name="name" />
            </flux:field>
            
            <flux:field>
                <flux:label for="description">Description</flux:label>
                <flux:textarea id="description" wire:model="description" rows="3"></flux:textarea>
                <flux:error name="description" />
            </flux:field>
            
            <flux:field variant="inline">
                <flux:label for="is_available">Available</flux:label>
                <flux:switch id="is_available" wire:model="is_available" />
                <flux:error name="is_available" />
            </flux:field>
            
            <flux:field>
                <flux:label for="quantity_available">Quantity Available (0 = Unlimited)</flux:label>
                <flux:input id="quantity_available" wire:model="quantity_available" type="number" min="0" />
                <flux:error name="quantity_available" />
            </flux:field>
            
            <div class="flex justify-end space-x-2">
                <flux:button wire:click="$set('isModalOpen', false)">Cancel</flux:button>
                <flux:button variant="primary" wire:click="save" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
