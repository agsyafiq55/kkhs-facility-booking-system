<div>
    <div class="py-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <flux:heading size="xl" level="1" class="text-gray-800 dark:text-white">{{ __('Edit Booking') }}</flux:heading>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Update booking details and status</p>
            </div>
            <div>
                <flux:button href="{{ route('admin.bookings.index') }}" wire:navigate class="transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline-block" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Bookings
                </flux:button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Booking Details Card -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-white">Booking Details</flux:heading>

                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Facility</span>
                            <span class="text-gray-900 dark:text-white">{{ $booking->facility->name }}</span>
                        </div>

                        @if($booking->subFacility)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Sub-Facility</span>
                            <span class="text-gray-900 dark:text-white">{{ $booking->subFacility->name }}</span>
                        </div>
                        @endif

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Booked By</span>
                            <span class="text-gray-900 dark:text-white">{{ $booking->user->name }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->user->email }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</span>
                            <span class="text-gray-900 dark:text-white">{{ $booking->date->format('d M Y') }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Time</span>
                            <span class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</span>
                            <span class="text-gray-900 dark:text-white">{{ $booking->created_at->format('d M Y, g:i A') }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Status</span>
                            <div class="mt-1">
                                @switch($booking->status)
                                @case('pending')
                                <flux:badge color="amber">Pending</flux:badge>
                                @break
                                @case('approved')
                                <flux:badge color="green">Approved</flux:badge>
                                @break
                                @case('rejected')
                                <flux:badge color="red">Rejected</flux:badge>
                                @break
                                @case('cancelled')
                                <flux:badge color="gray">Cancelled</flux:badge>
                                @break
                                @endswitch
                            </div>
                        </div>

                        @if($booking->notes)
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">User Notes</span>
                            <p class="text-gray-900 dark:text-white mt-1 text-sm bg-gray-50 dark:bg-zinc-700 p-3 rounded-md">
                                {{ $booking->notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Form Card -->
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4 text-gray-800 dark:text-white">Update Booking Status</flux:heading>

                    <form wire:submit="updateBooking" class="space-y-4">
                        <flux:field>
                            <flux:label>Booking Status</flux:label>
                            <flux:select wire:model="status">
                                <flux:select.option value="pending">Pending</flux:select.option>
                                <flux:select.option value="approved">Approved</flux:select.option>
                                <flux:select.option value="rejected">Rejected</flux:select.option>
                                <flux:select.option value="cancelled">Cancelled</flux:select.option>
                            </flux:select>
                            <flux:error name="status" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Admin Notes</flux:label>
                            <flux:textarea wire:model="notes" rows="4"></flux:textarea>
                            <flux:description>Add notes about this booking (optional)</flux:description>
                            <flux:error name="notes" />
                        </flux:field>

                        <flux:separator variant="subtle" class="my-6" />

                        <div class="flex justify-between items-center">
                            <flux:button variant="primary" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Update Booking
                            </flux:button>

                            <flux:modal.trigger name="delete-booking">
                                <flux:button variant="danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Delete Booking
                                </flux:button>
                            </flux:modal.trigger>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-booking" class="md:w-96">
        <div>
            <div>
                <flux:heading size="lg">Delete Booking?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this booking for <strong>{{ $booking->facility->name }}</strong>.</p>
                    <p>This action cannot be undone.</p>
                </flux:text>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="deleteBooking">
                    <span wire:loading.remove wire:target="deleteBooking">Delete Booking</span>
                    <span wire:loading wire:target="deleteBooking">Deleting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>