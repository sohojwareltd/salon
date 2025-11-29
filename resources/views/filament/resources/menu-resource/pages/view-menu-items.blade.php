<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Menu Information Card --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Menu Name</h4>
                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $record->title }}</p>
                    <code class="mt-1 text-xs text-gray-600 dark:text-gray-400">{{ $record->name }}</code>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h4>
                    <p class="mt-1">
                        <x-filament::badge 
                            :color="match($record->location) {
                                'header' => 'primary',
                                'footer_1', 'footer_2', 'footer_3' => 'success',
                                'sidebar' => 'warning',
                                default => 'gray'
                            }"
                            size="lg"
                        >
                            {{ match($record->location) {
                                'header' => 'Header Navigation',
                                'footer_1' => 'Footer Column 1',
                                'footer_2' => 'Footer Column 2',
                                'footer_3' => 'Footer Column 3',
                                'sidebar' => 'Sidebar',
                                default => $record->location
                            } }}
                        </x-filament::badge>
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                    <p class="mt-1 flex items-center gap-2">
                        @if($record->is_active)
                            <x-filament::badge color="success" icon="heroicon-o-check-circle" size="lg">
                                Active
                            </x-filament::badge>
                        @else
                            <x-filament::badge color="danger" icon="heroicon-o-x-circle" size="lg">
                                Inactive
                            </x-filament::badge>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Menu Items Table --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="bi bi-list-ul mr-2"></i>Menu Items
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Drag and drop to reorder menu items
                        </p>
                    </div>
                    <x-filament::badge color="info" size="lg">
                        {{ $record->items->count() }} item(s)
                    </x-filament::badge>
                </div>
            </div>
            
            <div class="p-6">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
