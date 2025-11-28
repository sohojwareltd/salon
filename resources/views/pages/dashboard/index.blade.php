@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-gray-600">Manage your appointments and bookings</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Upcoming</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $upcomingAppointments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $pastAppointments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $appointments->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        @if($upcomingAppointments->count() > 0)
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Upcoming Appointments</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($upcomingAppointments as $appointment)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $appointment->service->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    with {{ $appointment->provider->name }} at {{ $appointment->salon->name }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    📅 {{ $appointment->appointment_date->format('F j, Y') }} at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $appointment->status === 'confirmed' ? 'green' : 'yellow' }}-100 text-{{ $appointment->status === 'confirmed' ? 'green' : 'yellow' }}-800 mt-2">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <span class="text-lg font-bold text-indigo-600">{{ Settings::formatPrice($appointment->service->price) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- All Appointments -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">All Appointments</h2>
            </div>
            @if($appointments->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $appointment->service->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        with {{ $appointment->provider->name }} at {{ $appointment->salon->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        📅 {{ $appointment->appointment_date->format('F j, Y') }} at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                    </p>
                                    <div class="flex gap-2 mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($appointment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($appointment->payment_status === 'paid') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            Payment: {{ ucfirst($appointment->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 text-right">
                                    <span class="text-lg font-bold text-gray-900">{{ Settings::formatPrice($appointment->service->price) }}</span>
                                    @if($appointment->status === 'completed' && $appointment->payment_status === 'pending')
                                        <div class="mt-2">
                                            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Pay Now</button>
                                        </div>
                                    @endif
                                    @if($appointment->status === 'completed' && $appointment->payment_status === 'paid' && !$appointment->review)
                                        <div class="mt-2">
                                            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Leave Review</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500">No appointments yet.</p>
                    <a href="{{ route('providers.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Book Your First Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
