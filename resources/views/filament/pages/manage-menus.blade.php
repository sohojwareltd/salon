<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Navigation Section --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="bi bi-list-ul mr-2"></i>Header Navigation
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Main navigation menu displayed in the site header
                        </p>
                    </div>
                    <x-filament::badge color="primary">
                        {{ count($this->getMenusByLocation('header')) }} menu(s)
                    </x-filament::badge>
                </div>
            </div>
            <div class="p-6">
                @if(count($this->getMenusByLocation('header')) > 0)
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($this->getMenusByLocation('header') as $menu)
                            @include('filament.components.menu-card', ['menu' => $menu])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-inbox text-4xl mb-2"></i>
                        <p>No header menus created yet</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer Sections --}}
        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Footer Column 1 --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        <i class="bi bi-layout-text-sidebar-reverse mr-2"></i>Footer Column 1
                    </h3>
                    <x-filament::badge color="success" class="mt-2">
                        {{ count($this->getMenusByLocation('footer_1')) }} menu(s)
                    </x-filament::badge>
                </div>
                <div class="p-4">
                    @if(count($this->getMenusByLocation('footer_1')) > 0)
                        <div class="space-y-3">
                            @foreach($this->getMenusByLocation('footer_1') as $menu)
                                @include('filament.components.menu-card-compact', ['menu' => $menu])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500 text-sm">
                            <i class="bi bi-inbox text-2xl mb-1"></i>
                            <p>No menus</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Column 2 --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        <i class="bi bi-layout-text-sidebar mr-2"></i>Footer Column 2
                    </h3>
                    <x-filament::badge color="success" class="mt-2">
                        {{ count($this->getMenusByLocation('footer_2')) }} menu(s)
                    </x-filament::badge>
                </div>
                <div class="p-4">
                    @if(count($this->getMenusByLocation('footer_2')) > 0)
                        <div class="space-y-3">
                            @foreach($this->getMenusByLocation('footer_2') as $menu)
                                @include('filament.components.menu-card-compact', ['menu' => $menu])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500 text-sm">
                            <i class="bi bi-inbox text-2xl mb-1"></i>
                            <p>No menus</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Column 3 --}}
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        <i class="bi bi-layout-sidebar mr-2"></i>Footer Column 3
                    </h3>
                    <x-filament::badge color="success" class="mt-2">
                        {{ count($this->getMenusByLocation('footer_3')) }} menu(s)
                    </x-filament::badge>
                </div>
                <div class="p-4">
                    @if(count($this->getMenusByLocation('footer_3')) > 0)
                        <div class="space-y-3">
                            @foreach($this->getMenusByLocation('footer_3') as $menu)
                                @include('filament.components.menu-card-compact', ['menu' => $menu])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500 text-sm">
                            <i class="bi bi-inbox text-2xl mb-1"></i>
                            <p>No menus</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Section --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="bi bi-sidebar mr-2"></i>Sidebar Navigation
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Navigation menu for sidebar areas
                        </p>
                    </div>
                    <x-filament::badge color="warning">
                        {{ count($this->getMenusByLocation('sidebar')) }} menu(s)
                    </x-filament::badge>
                </div>
            </div>
            <div class="p-6">
                @if(count($this->getMenusByLocation('sidebar')) > 0)
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($this->getMenusByLocation('sidebar') as $menu)
                            @include('filament.components.menu-card', ['menu' => $menu])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-inbox text-4xl mb-2"></i>
                        <p>No sidebar menus created yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
