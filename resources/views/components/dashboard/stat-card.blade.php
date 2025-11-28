@props(['title', 'value', 'icon', 'color' => 'indigo', 'progress' => null, 'badge' => null])

@php
$colorClasses = [
    'green' => 'from-green-500 to-green-600 text-green-600',
    'yellow' => 'from-yellow-500 to-yellow-600 text-yellow-600',
    'indigo' => 'from-indigo-500 to-indigo-600 text-indigo-600',
    'blue' => 'from-blue-500 to-blue-600 text-blue-600',
    'purple' => 'from-purple-500 to-purple-600 text-purple-600',
];
$gradient = $colorClasses[$color] ?? $colorClasses['indigo'];
@endphp

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
            
            @if($progress)
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-600">{{ $progress['label'] }}</span>
                        <span class="font-semibold {{ explode(' ', $gradient)[2] }}">{{ $progress['text'] }}</span>
                    </div>
                    @if($progress['total'] > 0)
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r {{ explode(' text-', $gradient)[0] }} h-2 rounded-full" 
                             style="width: {{ $progress['percentage'] }}%"></div>
                    </div>
                    @endif
                </div>
            @endif

            @if($badge)
                <div class="flex items-center mt-2 space-x-2">
                    <span class="text-xs {{ $badge['class'] }} px-2 py-1 rounded-full font-medium">{{ $badge['text'] }}</span>
                    @if(isset($badge['extra']))
                        <span class="text-xs {{ $badge['extra']['class'] }}">{{ $badge['extra']['text'] }}</span>
                    @endif
                </div>
            @endif
        </div>
        
        <div class="w-14 h-14 bg-gradient-to-br {{ explode(' text-', $gradient)[0] }} rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    </div>
</div>
