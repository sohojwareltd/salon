<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-3">
    <div class="flex items-center justify-between mb-2">
        <div class="flex-1 min-w-0">
            <h5 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                {{ $menu['title'] }}
            </h5>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ count($menu['items'] ?? []) }} items
            </p>
        </div>
        @if($menu['is_active'])
            <x-filament::badge color="success" size="sm">Active</x-filament::badge>
        @else
            <x-filament::badge color="gray" size="sm">Inactive</x-filament::badge>
        @endif
    </div>

    <div class="flex gap-1.5">
        <x-filament::button
            tag="a"
            href="{{ \App\Filament\Resources\MenuResource::getUrl('edit', ['record' => $menu['id']]) }}"
            color="primary"
            size="xs"
            icon="heroicon-o-pencil"
        >
            Edit
        </x-filament::button>
        
        <x-filament::button
            wire:click="toggleMenuStatus({{ $menu['id'] }})"
            color="gray"
            size="xs"
            icon="heroicon-o-eye{{ $menu['is_active'] ? '-slash' : '' }}"
        >
        </x-filament::button>
    </div>
</div>
