@extends('layouts.app')

@section('title', $provider ? 'Book Appointment with ' . $provider->name : 'Book Appointment')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .booking-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 0;
        }

        .booking-header-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #f0f0f0;
        }

        .booking-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 32px;
            border: 1px solid #f0f0f0;
        }

        .form-label-modern {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control-modern {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control-modern:focus {
            border-color: #872341;
            box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
            outline: none;
        }

        .time-slot-btn {
            padding: 14px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .time-slot-btn:hover {
            border-color: #872341;
            background: #fef2f2;
            transform: translateY(-2px);
        }

        .time-slot-btn.selected {
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            border-color: #872341;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 24px;
            border: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }

        .provider-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary-custom:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(135, 35, 65, 0.4);
        }

        .btn-primary-custom:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary-custom {
            background: #f3f4f6;
            color: #6b7280;
            border: 2px solid #e5e7eb;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-secondary-custom:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            color: #065f46;
            font-weight: 500;
        }

        .alert-warning-custom {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            color: #92400e;
        }

        .loading-spinner {
            text-align: center;
            padding: 48px 20px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #872341;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Service Checkbox Cards */
        .service-checkbox-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        .service-checkbox {
            width: 22px;
            height: 22px;
            cursor: pointer;
            accent-color: #872341;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .service-checkbox-card:hover {
            border-color: #BE3144;
            box-shadow: 0 6px 16px rgba(190, 49, 68, 0.2);
            transform: translateY(-3px);
        }

        .service-checkbox-card.selected {
            border-color: #872341;
            background: linear-gradient(135deg, rgba(135, 35, 65, 0.08), rgba(190, 49, 68, 0.08));
            box-shadow: 0 6px 20px rgba(135, 35, 65, 0.25);
            transform: translateY(-2px);
        }

        .service-checkbox-card.selected .service-checkbox {
            transform: scale(1.1);
        }

        .service-check-icon {
            display: none;
        }

        .service-info {
            flex: 1;
            min-width: 0;
        }

        .service-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .service-checkbox-card.selected .service-name {
            color: #872341;
        }

        .service-details {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: #6b7280;
            flex-wrap: wrap;
        }

        .service-details span {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f9fafb;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 500;
        }

        .service-checkbox-card.selected .service-details span {
            background: rgba(135, 35, 65, 0.1);
            color: #872341;
        }

        .service-details i {
            font-size: 13px;
        }
    </style>

    <div class="booking-container">
        <div class="container" style="max-width: 1400px;">
            <!-- Header -->
            <div class="booking-header-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <div>
                            <h1 style="font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 8px; line-height: 1.2;">
                                <i class="bi bi-calendar-check-fill me-2" style="color: #872341;"></i>Book Appointment
                            </h1>
                            <p style="color: #6b7280; margin: 0; font-size: 15px;">
                                @if($provider)
                                    Schedule your appointment with <strong style="color: #872341;">{{ $provider->name }}</strong>
                                @else
                                    Select a provider and book your appointment
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($provider)
                        <div class="d-flex gap-2">
                            <span style="background: linear-gradient(135deg, rgba(135, 35, 65, 0.1), rgba(190, 49, 68, 0.1)); color: #872341; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; border: 2px solid rgba(135, 35, 65, 0.2);">
                                <i class="bi bi-star-fill me-1"></i>{{ number_format($provider->average_rating, 1) }} Rating
                            </span>
                            <span style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); color: #059669; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; border: 2px solid rgba(16, 185, 129, 0.2);">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified Provider
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            @if ($provider)
                    <div class="row g-4">
                        <!-- Main Form -->
                        <div class="col-12 col-lg-8">
                            <div class="booking-form-card">
                                <form action="{{ route('appointments.store') }}" method="POST" x-data="bookingForm()">
                                    @csrf
                                    <input type="hidden" name="provider_id" value="{{ $provider->id }}">

                                    <!-- Success Message -->
                                    @if (session('success'))
                                        <div class="alert-success-custom d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill" style="font-size: 20px;"></i>
                                            <span>{{ session('success') }}</span>
                                        </div>
                                    @endif

                                    <!-- Service Selection -->
                                    <div class="mb-5">
                                        <label class="form-label-modern" style="font-size: 18px; margin-bottom: 8px;">
                                            <i class="bi bi-scissors" style="color: #872341; font-size: 20px;"></i>
                                            Select Services <span class="text-danger">*</span>
                                        </label>
                                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px; line-height: 1.6;">
                                            <i class="bi bi-info-circle-fill me-1" style="color: #3b82f6;"></i>
                                            Choose one or multiple services you'd like to book. Total time and price will be
                                            calculated automatically.
                                        </p>

                                        <div
                                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px;">
                                            @foreach ($provider->services as $service)
                                                <div class="service-checkbox-card"
                                                    :class="{ 'selected': selectedServices.includes({{ $service->id }}) }"
                                                    @click="toggleServiceSelection({{ $service->id }}, {{ $service->duration }}, {{ $service->price }})">
                                                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                                        data-duration="{{ $service->duration }}"
                                                        data-price="{{ $service->price }}"
                                                        data-name="{{ $service->name }}"
                                                        :checked="selectedServices.includes({{ $service->id }})"
                                                        class="service-checkbox" @click.stop>
                                                    <div class="service-info">
                                                        <div class="service-name">{{ $service->name }}</div>
                                                        <div class="service-details">
                                                            <span><i class="bi bi-clock-fill"></i> {{ $service->duration }}
                                                                min</span>
                                                            <span><i class="bi bi-cash"></i>
                                                                {{ Settings::formatPrice($service->price, false) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @error('service_ids')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror

                                        <!-- Selected Services Summary -->
                                        <div x-show="selectedServices.length > 0" x-transition class="mt-3 p-4"
                                            style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 2px solid #10b981; border-radius: 12px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                                <div style="font-size: 15px; font-weight: 600; color: #065f46;">
                                                    <i class="bi bi-check-circle-fill me-1"></i>
                                                    Selected Services
                                                </div>
                                                <span
                                                    style="background: #065f46; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                                    <span x-text="selectedServices.length"></span>
                                                </span>
                                            </div>

                                            <!-- Service Names List -->
                                            <div
                                                style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #86efac;">
                                                <template x-for="serviceId in selectedServices" :key="serviceId">
                                                    <div
                                                        style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                                        <i class="bi bi-check2"
                                                            style="color: #10b981; font-size: 16px; font-weight: bold;"></i>
                                                        <span style="font-size: 13px; color: #047857; font-weight: 500;"
                                                            x-text="getServiceName(serviceId)"></span>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Totals -->
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                                <div
                                                    style="background: rgba(255,255,255,0.6); padding: 10px 12px; border-radius: 8px;">
                                                    <div
                                                        style="font-size: 11px; color: #047857; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                                        Total Duration</div>
                                                    <div style="font-size: 18px; font-weight: 700; color: #065f46;">
                                                        <i class="bi bi-clock me-1" style="font-size: 16px;"></i>
                                                        <span x-text="totalDuration"></span> min
                                                    </div>
                                                </div>
                                                <div
                                                    style="background: rgba(255,255,255,0.6); padding: 10px 12px; border-radius: 8px;">
                                                    <div
                                                        style="font-size: 11px; color: #047857; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                                        Total Price</div>
                                                    <div style="font-size: 18px; font-weight: 700; color: #065f46;">
                                                        <i class="bi bi-currency-dollar me-1"
                                                            style="font-size: 16px;"></i>
                                                        {{ Settings::currency() }}<span
                                                            x-text="totalPrice.toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date Selection -->
                                    <div class="mb-5">
                                        <label for="appointment_date" class="form-label-modern"
                                            style="font-size: 18px; margin-bottom: 8px;">
                                            <i class="bi bi-calendar-event" style="color: #872341; font-size: 20px;"></i>
                                            Select Date <span class="text-danger">*</span>
                                        </label>
                                        <p style="font-size: 13px; color: #6b7280; margin-bottom: 12px;">
                                            <i class="bi bi-info-circle-fill me-1" style="color: #3b82f6;"></i>
                                            Pick a date for your appointment. Available time slots will be shown based on
                                            your selection.
                                        </p>
                                        <input x-model="appointmentDate" @change="loadSlots()" type="date"
                                            id="appointment_date" name="appointment_date" :min="minDate"
                                            value="{{ old('appointment_date') }}" required
                                            class="form-control-modern @error('appointment_date') border-danger @enderror"
                                            style="max-width: 400px;">
                                        @error('appointment_date')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Loading Indicator -->
                                    <div x-show="loading" class="loading-spinner mb-4">
                                        <div class="spinner"></div>
                                        <p class="mt-3" style="color: #6b7280; font-size: 14px;">Loading available time
                                            slots...</p>
                                    </div>

                                    <!-- Time Slots -->
                                    <div x-show="slots.length > 0 && !loading" class="mb-5">
                                        <label class="form-label-modern mb-3" style="font-size: 18px;">
                                            <i class="bi bi-clock" style="color: #872341; font-size: 20px;"></i>
                                            Select Time <span class="text-danger">*</span>
                                        </label>
                                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px; line-height: 1.6;">
                                            <i class="bi bi-info-circle-fill me-1" style="color: #3b82f6;"></i>
                                            Available time slots for <strong style="color: #872341;"><span
                                                    x-text="totalDuration"></span> minutes</strong> of service. Each slot
                                            shows start and end time.
                                        </p>
                                        <div class="row g-2">
                                            <template x-for="slot in slots" :key="slot">
                                                <div class="col-6 col-sm-4 col-md-3">
                                                    <button type="button" @click="selectSlot(slot)"
                                                        :class="selectedSlot === slot ? 'selected' : ''"
                                                        class="time-slot-btn w-100"
                                                        style="padding: 12px 8px; height: auto;">
                                                        <div style="font-size: 14px; font-weight: 700; margin-bottom: 2px;"
                                                            x-text="formatTime(slot)"></div>
                                                        <div style="font-size: 11px; opacity: 0.75;"
                                                            x-text="formatSlotRange(slot)"></div>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        <input type="hidden" name="start_time" x-model="selectedSlot">
                                        @error('start_time')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- No Slots Message -->
                                    {{-- <div x-show="!loading && selectedServices.length > 0 && appointmentDate && slots.length === 0"
                                        class="alert-warning-custom mb-4 d-flex align-items-start gap-2">
                                        <i class="bi bi-exclamation-triangle-fill"
                                            style="font-size: 20px; margin-top: 2px;"></i>
                                        <div>
                                            <p style="font-weight: 600; margin-bottom: 6px;">No available time slots</p>
                                            <p style="font-size: 13px; margin: 0;">The salon may be closed or fully booked
                                                on this date. Please try another date.</p>
                                        </div>
                                    </div> --}}

                                    <!-- Error Messages -->
                                    @if ($errors->any())
                                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex">
                                                <svg class="h-5 w-5 text-red-400 mt-0.5" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="ml-3">
                                                    @foreach ($errors->all() as $error)
                                                        <p class="text-sm text-red-700">{{ $error }}</p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Submit Button -->
                                    <div class="row g-3 mt-3">
                                        <div class="col-12 col-sm-6">
                                            <a href="{{ route('providers.show', $provider) }}"
                                                class="btn-secondary-custom text-center text-decoration-none d-block">
                                                <i class="bi bi-x-circle me-2"></i>Cancel
                                            </a>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <button type="submit"
                                                :disabled="selectedServices.length === 0 || !appointmentDate || !selectedSlot"
                                                class="btn-primary-custom">
                                                <i class="bi bi-check-circle me-2"></i>Book Appointment
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-12 col-lg-4">
                            <!-- Providers List -->
                            @if($providers && $providers->count() > 0)
                                <div class="sidebar-card mb-4" style="background: linear-gradient(135deg, rgba(135, 35, 65, 0.03), rgba(190, 49, 68, 0.03)); border: 2px solid rgba(135, 35, 65, 0.1);">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                                        <i class="bi bi-people-fill" style="font-size: 20px; color: #872341;"></i>
                                        <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Our Professionals</h3>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 12px; max-height: 500px; overflow-y: auto; padding-right: 8px;">
                                        @foreach($providers as $prov)
                                            <a href="{{ route('appointments.book', $prov) }}" style="text-decoration: none;">
                                                <div style="padding: 14px; border-radius: 12px; transition: all 0.3s; {{ $provider && $provider->id === $prov->id ? 'background: linear-gradient(135deg, rgba(135, 35, 65, 0.15), rgba(190, 49, 68, 0.15)); border: 2px solid #872341;' : 'background: white; border: 2px solid #e5e7eb;' }}"
                                                     onmouseover="if({{ $provider && $provider->id === $prov->id ? 'false' : 'true' }}) { this.style.borderColor='#872341'; this.style.transform='translateX(5px)'; }"
                                                     onmouseout="if({{ $provider && $provider->id === $prov->id ? 'false' : 'true' }}) { this.style.borderColor='#e5e7eb'; this.style.transform='translateX(0)'; }">
                                                    <div class="d-flex gap-3 align-items-center">
                                                        @if($prov->photo)
                                                            <img src="{{ asset('storage/' . $prov->photo) }}" alt="{{ $prov->name }}" 
                                                                 style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 3px solid {{ $provider && $provider->id === $prov->id ? '#872341' : '#e5e7eb' }};">
                                                        @else
                                                            <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; border: 3px solid {{ $provider && $provider->id === $prov->id ? '#872341' : 'transparent' }};">
                                                                {{ strtoupper(substr($prov->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h4 style="font-weight: 700; color: {{ $provider && $provider->id === $prov->id ? '#872341' : '#111827' }}; margin-bottom: 4px; font-size: 15px;">
                                                                {{ $prov->name }}
                                                                @if($provider && $provider->id === $prov->id)
                                                                    <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 14px;"></i>
                                                                @endif
                                                            </h4>
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <div style="display: flex; align-items: center; gap: 3px;">
                                                                    <i class="bi bi-star-fill" style="color: #f59e0b; font-size: 12px;"></i>
                                                                    <span style="font-size: 12px; color: #92400e; font-weight: 600;">{{ number_format($prov->average_rating, 1) }}</span>
                                                                </div>
                                                                <span style="color: #9ca3af;">•</span>
                                                                <span style="font-size: 12px; color: #6b7280;">{{ $prov->services->count() }} services</span>
                                                            </div>
                                                            @if($prov->expertise)
                                                                <p style="font-size: 11px; color: #6b7280; margin: 0; line-height: 1.3;">
                                                                    <i class="bi bi-award me-1" style="font-size: 10px;"></i>{{ Illuminate\Support\Str::limit($prov->expertise, 30) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        @if(!$provider || $provider->id !== $prov->id)
                                                            <i class="bi bi-chevron-right" style="color: #6b7280; font-size: 14px;"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Provider Card -->
                            <div class="sidebar-card"
                                style="background: linear-gradient(135deg, rgba(135, 35, 65, 0.03), rgba(190, 49, 68, 0.03)); border: 2px solid rgba(135, 35, 65, 0.1);">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                                    <i class="bi bi-person-badge-fill" style="font-size: 20px; color: #872341;"></i>
                                    <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Provider
                                        Details</h3>
                                </div>
                                <div class="d-flex gap-3 mb-3">
                                    @if ($provider->photo)
                                        <img src="{{ asset('storage/' . $provider->photo) }}"
                                            alt="{{ $provider->name }}" class="provider-avatar-large"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="provider-avatar-large">
                                            {{ strtoupper(substr($provider->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h4 style="font-weight: 700; color: #111827; margin-bottom: 6px; font-size: 18px;">
                                            {{ $provider->name }}</h4>
                                        <p style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                                            <i class="bi bi-award-fill me-1" style="color: #872341;"></i>
                                            {{ $provider->expertise ?? 'Professional Barber' }}
                                        </p>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="display: flex; align-items: center; gap: 4px; background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 4px 10px; border-radius: 20px; border: 1px solid #f59e0b;">
                                                <i class="bi bi-star-fill" style="color: #f59e0b; font-size: 14px;"></i>
                                                <span
                                                    style="font-size: 13px; color: #92400e; font-weight: 700;">{{ number_format($provider->average_rating, 1) }}</span>
                                            </div>
                                            @if ($provider->total_reviews > 0)
                                                <span
                                                    style="font-size: 13px; color: #6b7280;">({{ $provider->total_reviews }}
                                                    reviews)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($provider->bio)
                                    <div
                                        style="padding: 12px; background: rgba(255,255,255,0.7); border-radius: 10px; border-left: 3px solid #872341;">
                                        <p style="font-size: 13px; color: #4b5563; margin: 0; line-height: 1.6;">
                                            <i class="bi bi-quote" style="color: #872341;"></i>
                                            {{  Illuminate\Support\Str::limit($provider->bio, 120) }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                            <!-- Provider Contact -->

                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                                    <i class="bi bi-info-circle-fill" style="font-size: 20px; color: #3b82f6;"></i>
                                    <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Booking Information</h3>
                                </div>

                                <div class="d-flex flex-column gap-3">
                                    @if($provider->phone)
                                    <div class="d-flex gap-3 align-items-center p-3"
                                        style="background: rgba(255,255,255,0.7); border-radius: 12px;">
                                        <div
                                            style="width: 40px; height: 40px; min-width: 40px; background: rgba(16, 185, 129, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(16, 185, 129, 0.2);">
                                            <i class="bi bi-telephone-fill" style="color: #10b981; font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <p style="font-size: 12px; color: #6b7280; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Contact Provider</p>
                                            <a href="tel:{{ $provider->phone }}"
                                                style="font-size: 15px; color: #111827; font-weight: 600; text-decoration: none; transition: color 0.3s;"
                                                onmouseover="this.style.color='#10b981'"
                                                onmouseout="this.style.color='#111827'">{{ $provider->phone }}</a>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="p-3"
                                        style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(245, 158, 11, 0.1)); border-radius: 12px; border: 2px solid rgba(251, 191, 36, 0.2);">
                                        <div class="d-flex gap-2 align-items-start">
                                            <i class="bi bi-info-circle-fill" style="color: #f59e0b; font-size: 18px; margin-top: 2px;"></i>
                                            <div class="flex-grow-1">
                                                <p style="font-weight: 700; color: #111827; margin-bottom: 8px; font-size: 14px;">Important Notes</p>
                                                <ul style="font-size: 13px; color: #6b7280; margin: 0; padding-left: 20px; line-height: 1.8;">
                                                    <li>Please arrive 5-10 minutes early</li>
                                                    <li>Cancellations must be made 24 hours in advance</li>
                                                    <li>Payment is required after service completion</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
    </div>

    @if ($provider)
        @push('scripts')
            <script>
                function bookingForm() {
                    return {
                        selectedServices: [],
                        serviceData: {},
                        appointmentDate: '{{ old('appointment_date') }}',
                        selectedSlot: '{{ old('start_time') }}',
                        slots: [],
                        loading: false,
                        minDate: new Date().toISOString().split('T')[0],
                        totalDuration: 0,
                        totalPrice: 0,

                        toggleServiceSelection(serviceId, duration, price) {
                            const index = this.selectedServices.indexOf(serviceId);

                            if (index > -1) {
                                // Remove service
                                this.selectedServices.splice(index, 1);
                                this.totalDuration -= duration;
                                this.totalPrice -= price;
                            } else {
                                // Add service
                                this.selectedServices.push(serviceId);
                                this.totalDuration += duration;
                                this.totalPrice += price;
                            }

                            // Reset slots when services change
                            this.selectedSlot = '';
                            this.slots = [];

                            if (this.appointmentDate && this.selectedServices.length > 0) {
                                this.loadSlots();
                            }
                        },

                        getServiceName(serviceId) {
                            return this.serviceData[serviceId] || 'Service';
                        },

                        calculateTotals() {
                            let duration = 0;
                            let price = 0;

                            const checkboxes = document.querySelectorAll('input[name="service_ids[]"]:checked');
                            checkboxes.forEach(checkbox => {
                                duration += parseInt(checkbox.dataset.duration);
                                price += parseFloat(checkbox.dataset.price);
                            });

                            this.totalDuration = duration;
                            this.totalPrice = price;
                        },

                        selectSlot(slot) {
                            this.selectedSlot = slot;
                        },

                        formatTime(time) {
                            // Convert 24h format (HH:mm:ss) to 12h format (h:mm AM/PM)
                            const [hours, minutes] = time.split(':');
                            const hour = parseInt(hours);
                            const ampm = hour >= 12 ? 'PM' : 'AM';
                            const displayHour = hour % 12 || 12;
                            return `${displayHour}:${minutes} ${ampm}`;
                        },

                        formatSlotRange(startTime) {
                            // Calculate end time based on total duration
                            const [hours, minutes] = startTime.split(':').map(Number);
                            const startMinutes = hours * 60 + minutes;
                            const endMinutes = startMinutes + this.totalDuration;
                            const endHours = Math.floor(endMinutes / 60);
                            const endMins = endMinutes % 60;

                            const endTime = `${String(endHours).padStart(2, '0')}:${String(endMins).padStart(2, '0')}`;
                            return `to ${this.formatTime(endTime)}`;
                        },

                        loadSlots() {
                            if (this.selectedServices.length === 0 || !this.appointmentDate) {
                                return;
                            }

                            this.loading = true;
                            this.slots = [];
                            this.selectedSlot = '';

                            const serviceIds = this.selectedServices.join(',');

                            fetch(`/appointments/available-slots/{{ $provider->id }}?service_ids=${serviceIds}&date=${this.appointmentDate}`, {
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.slots = data.data.slots || [];
                                    } else {
                                        console.error('API Error:', data);
                                    }
                                })
                                .catch(error => {
                                    console.error('Failed to load slots:', error);
                                })
                                .finally(() => {
                                    this.loading = false;
                                });
                        },

                        init() {
                            // Store service data for lookup
                            document.querySelectorAll('input[name="service_ids[]"]').forEach(checkbox => {
                                this.serviceData[checkbox.value] = checkbox.dataset.name;
                            });

                            this.calculateTotals();
                            if (this.selectedServices.length > 0 && this.appointmentDate) {
                                this.loadSlots();
                            }
                        }
                    }
                }
            </script>
        @endpush
    @endif
@endsection
