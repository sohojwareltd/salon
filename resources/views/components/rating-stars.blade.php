@props(['rating' => 0, 'readonly' => false, 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
@endphp

<div class="flex items-center space-x-1" {{ $readonly ? '' : 'x-data="{ rating: ' . $rating . ' }"' }}>
    @for($i = 1; $i <= $fullStars; $i++)
        <svg class="{{ $sizeClass }} text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endfor
    
    @if($hasHalfStar)
        <svg class="{{ $sizeClass }} text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <defs>
                <linearGradient id="half-star-{{ $rating }}">
                    <stop offset="50%" stop-color="currentColor"/>
                    <stop offset="50%" stop-color="#D1D5DB" stop-opacity="1"/>
                </linearGradient>
            </defs>
            <path fill="url(#half-star-{{ $rating }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endif
    
    @for($i = 1; $i <= $emptyStars; $i++)
        <svg class="{{ $sizeClass }} text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endfor
    
    @if($rating > 0)
        <span class="text-sm font-medium text-gray-700 ml-1">{{ number_format($rating, 1) }}</span>
    @endif
</div>
