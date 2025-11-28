@props(['route', 'active' => false, 'icon'])

<a href="{{ $route }}" 
   class="flex items-center px-4 py-3 rounded-lg transition mb-1 {{ $active ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icon !!}
    </svg>
    <span>{{ $slot }}</span>
</a>
