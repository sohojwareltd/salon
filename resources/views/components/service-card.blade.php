@props(['service'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    @if($service->image)
        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
    @else
        <div class="w-full h-48 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
            <span class="text-6xl">💇</span>
        </div>
    @endif
    
    <div class="p-6">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ $service->category }}
            </span>
        </div>
        
        @if($service->description)
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $service->description }}</p>
        @endif
        
        <div class="flex justify-between items-center text-sm">
            <div class="flex items-center text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $service->duration }} min</span>
            </div>
            
            <div class="text-xl font-bold text-indigo-600">
                {{ App\Facades\Settings::formatPrice($service->price) }}
            </div>
        </div>
    </div>
</div>
