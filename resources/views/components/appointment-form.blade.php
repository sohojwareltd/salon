@props(['provider', 'services' => []])

<div x-data="appointmentForm()" @open-appointment-modal.window="openModal()" x-cloak>
    <!-- Trigger Button (hidden, controlled by parent) -->
    <div style="display: none;"></div>

    <!-- Modal -->
    <div x-show="open" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-white">
                                Book Appointment with {{ $provider->name }}
                            </h3>
                        </div>
                        <button @click="closeModal()" class="text-white hover:text-gray-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-6">

                    <!-- Success Message -->
                    <div x-show="success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="ml-3 text-sm text-green-700">Appointment booked successfully! Redirecting...</p>
                        </div>
                    </div>

                    <form action="{{ route('appointments.store') }}" method="POST" @submit.prevent="submitForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                        <input type="hidden" name="salon_id" value="{{ $provider->salon_id }}">
                        
                        <!-- Service Selection -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="service_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                <svg class="inline h-5 w-5 text-indigo-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Select Service
                            </label>
                            <select x-model="formData.service_id" @change="serviceChanged()" id="service_id" name="service_id" required class="mt-1 block w-full pl-4 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm">
                                <option value="">Choose a service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-duration="{{ $service->duration }}" data-price="{{ $service->price }}">
                                        {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} min)
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="selectedService" class="mt-2 text-sm text-gray-600" x-text="selectedService"></p>
                        </div>

                        <!-- Date Selection -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="appointment_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                <svg class="inline h-5 w-5 text-indigo-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Select Date
                            </label>
                            <input x-model="formData.appointment_date" @change="loadAvailableSlots()" type="date" id="appointment_date" name="appointment_date" :min="minDate" required class="mt-1 block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Loading Indicator -->
                        <div x-show="loading" class="text-center py-6">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                            <p class="mt-2 text-sm text-gray-600">Loading available time slots...</p>
                        </div>

                        <!-- Time Selection -->
                        <div x-show="availableSlots.length > 0 && !loading" class="bg-gray-50 p-4 rounded-lg">
                            <label for="start_time" class="block text-sm font-semibold text-gray-900 mb-2">
                                <svg class="inline h-5 w-5 text-indigo-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Select Time
                            </label>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <template x-for="slot in availableSlots" :key="slot">
                                    <button type="button" @click="selectTimeSlot(slot)" :class="formData.start_time === slot ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-indigo-50'" class="px-4 py-3 text-sm font-medium rounded-lg border border-gray-300 transition-colors duration-150">
                                        <span x-text="slot"></span>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="start_time" x-model="formData.start_time">
                        </div>

                        <!-- No Slots Message -->
                        <div x-show="!loading && formData.service_id && formData.appointment_date && availableSlots.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="ml-3 text-sm text-yellow-700">No available time slots for this date. Please select a different date.</p>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div x-show="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="ml-3 text-sm text-red-700" x-text="error"></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="closeModal()" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                Cancel
                            </button>
                            <button type="submit" :disabled="loading || !formData.service_id || !formData.appointment_date || !formData.start_time" class="flex-1 px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150">
                                <span x-show="!loading">Book Appointment</span>
                                <span x-show="loading">Booking...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function appointmentForm() {
    return {
        open: false,
        loading: false,
        error: '',
        success: false,
        availableSlots: [],
        selectedService: '',
        minDate: new Date().toISOString().split('T')[0],
        formData: {
            provider_id: '{{ $provider->id }}',
            service_id: '',
            appointment_date: '',
            start_time: ''
        },
        
        openModal() {
            this.open = true;
            this.resetForm();
        },
        
        closeModal() {
            this.open = false;
            this.resetForm();
        },
        
        resetForm() {
            this.formData.service_id = '';
            this.formData.appointment_date = '';
            this.formData.start_time = '';
            this.availableSlots = [];
            this.error = '';
            this.success = false;
            this.selectedService = '';
        },
        
        serviceChanged() {
            const select = document.getElementById('service_id');
            const option = select.options[select.selectedIndex];
            if (option.value) {
                this.selectedService = `Duration: ${option.dataset.duration} minutes | Price: $${parseFloat(option.dataset.price).toFixed(2)}`;
                this.formData.start_time = '';
                this.availableSlots = [];
                if (this.formData.appointment_date) {
                    this.loadAvailableSlots();
                }
            } else {
                this.selectedService = '';
            }
        },
        
        selectTimeSlot(slot) {
            this.formData.start_time = slot;
        },
        
        async loadAvailableSlots() {
            if (!this.formData.service_id || !this.formData.appointment_date) {
                return;
            }
            
            this.loading = true;
            this.error = '';
            this.availableSlots = [];
            this.formData.start_time = '';
            
            try {
                const response = await fetch(`/api/providers/{{ $provider->id }}/available-slots?service_id=${this.formData.service_id}&date=${this.formData.appointment_date}`);
                const data = await response.json();
                
                if (response.ok && data.success) {
                    this.availableSlots = data.data.slots || [];
                    if (this.availableSlots.length === 0) {
                        this.error = '';
                    }
                } else {
                    this.error = data.message || 'Failed to load available slots';
                }
            } catch (error) {
                this.error = 'Failed to load available slots. Please try again.';
            } finally {
                this.loading = false;
            }
        },
        
        async submitForm(event) {
            if (!this.formData.service_id || !this.formData.appointment_date || !this.formData.start_time) {
                this.error = 'Please fill in all required fields';
                return;
            }
            
            this.loading = true;
            this.error = '';
            
            const formData = new FormData(event.target);
            
            try {
                const response = await fetch('{{ route("appointments.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    this.success = true;
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    this.error = data.message || 'Failed to book appointment. Please try again.';
                }
            } catch (error) {
                this.error = 'An error occurred while booking your appointment. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
