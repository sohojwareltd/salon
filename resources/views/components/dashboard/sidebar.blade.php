@props(['role', 'activeRoute'])

<div class="flex flex-col h-full">
    <!-- Sidebar Header -->
    <div class="p-6 border-b">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ config('app.name') }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ ucfirst($role) }} Dashboard</p>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto">
        {{ $slot }}
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t bg-gray-50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-gray-600 hover:text-indigo-600 font-medium transition">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
