<div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4">
    <div class="flex items-start justify-between mb-3">
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                    {{ $menu['title'] }}
                </h4>
                @if($menu['is_active'])
                    <x-filament::badge color="success">Active</x-filament::badge>
                @else
                    <x-filament::badge color="gray">Inactive</x-filament::badge>
                @endif
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                <code class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $menu['name'] }}</code>
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 mb-3">
        <span class="flex items-center gap-1">
            <i class="bi bi-list-task"></i>
            {{ count($menu['items'] ?? []) }} items
        </span>
        <span class="flex items-center gap-1">
            <i class="bi bi-sort-numeric-down"></i>
            Order: {{ $menu['sort_order'] }}
        </span>
    </div>

    @if(count($menu['items'] ?? []) > 0)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mb-3">
            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Menu Items:</p>
            <div class="space-y-1">
                @foreach(collect($menu['items'])->sortBy('sort_order')->take(3) as $item)
                    <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                        @if($item['icon'] ?? null)
                            <i class="{{ $item['icon'] }}"></i>
                        @endif
                        <span class="truncate">{{ $item['label'] }}</span>
                    </div>
                @endforeach
                @if(count($menu['items']) > 3)
                    <p class="text-xs text-gray-500 italic">+ {{ count($menu['items']) - 3 }} more</p>
                @endif
            </div>
        </div>
    @endif

    <div class="flex gap-2">
        <x-filament::button
            tag="a"
            href="{{ \App\Filament\Resources\MenuResource::getUrl('edit', ['record' => $menu['id']]) }}"
            color="primary"
            size="sm"
            icon="heroicon-o-pencil"
        >
            Edit
        </x-filament::button>
        
        <x-filament::button
            wire:click="toggleMenuStatus({{ $menu['id'] }})"
            color="gray"
            size="sm"
            icon="heroicon-o-eye{{ $menu['is_active'] ? '-slash' : '' }}"
        >
            {{ $menu['is_active'] ? 'Disable' : 'Enable' }}
        </x-filament::button>
    </div>
</div>
