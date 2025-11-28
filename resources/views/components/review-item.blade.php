@props(['review'])

<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="flex items-start justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-lg font-bold">
                {{ substr($review->user->name, 0, 1) }}
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </div>
        
        @include('components.rating-stars', ['rating' => $review->rating, 'readonly' => true, 'size' => 'sm'])
    </div>
    
    @if($review->comment)
        <p class="mt-4 text-gray-700">{{ $review->comment }}</p>
    @endif
    
    @if($review->appointment)
        <p class="mt-2 text-xs text-gray-500">
            Service: {{ $review->appointment->service->name }}
        </p>
    @endif
</div>
