@extends('layouts.provider-dashboard')

@section('content')
<style>
    .settings-header {
        margin-bottom: 28px;
    }
    
    .settings-title {
        font-size: 28px;
        font-weight: 700;
        color: #09122C;
        margin: 0;
    }
    
    .settings-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 20px 24px;
        border-bottom: none;
    }
    
    .card-header-modern h5 {
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-body-modern {
        padding: 28px;
    }
    
    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #09122C;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-title i {
        color: #872341;
        font-size: 20px;
    }
    
    .form-label-custom {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control-custom:focus {
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
        outline: none;
    }
    
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
    }
    
    .schedule-day-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.3s ease;
    }
    
    .schedule-day-card.active {
        background: #fff;
        border-color: #872341;
        box-shadow: 0 2px 8px rgba(135, 35, 65, 0.1);
    }
    
    .schedule-day-card.off {
        opacity: 0.5;
    }
    
    .day-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    
    .day-name {
        font-size: 15px;
        font-weight: 600;
        color: #09122C;
    }
    
    .day-toggle {
        width: 50px;
        height: 26px;
        position: relative;
        display: inline-block;
    }
    
    .day-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 26px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    
    .day-toggle input:checked + .toggle-slider {
        background: linear-gradient(135deg, #872341, #BE3144);
    }
    
    .day-toggle input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .time-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    .time-input-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .time-input-label {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
    }
    
    .time-input {
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    
    .time-input:focus {
        border-color: #872341;
        outline: none;
    }
    
    .break-time-section {
        background: #fef3c7;
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .break-time-section.disabled {
        background: #f9fafb;
        border-color: #e5e7eb;
        opacity: 0.6;
    }
    
    .checkbox-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }
    
    .checkbox-custom input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #872341;
    }
    
    .checkbox-custom label {
        font-size: 15px;
        font-weight: 600;
        color: #09122C;
        cursor: pointer;
        margin: 0;
    }
    
    .social-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    
    .social-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        flex-shrink: 0;
    }
    
    .social-icon.facebook { background: #1877F2; }
    .social-icon.instagram { background: linear-gradient(45deg, #F58529, #DD2A7B, #8134AF); }
    .social-icon.twitter { background: #1DA1F2; }
    .social-icon.youtube { background: #FF0000; }
    .social-icon.linkedin { background: #0A66C2; }
    .social-icon.website { background: #6366F1; }
    
    .btn-save {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: #fff;
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }
    
    .alert-success-custom {
        background: #D1FAE5;
        border: 2px solid #10B981;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        color: #065F46;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    /* ===== RESPONSIVE TABS ===== */
    .settings-tabs-container {
        background: white;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .settings-tabs-header {
        display: flex;
        gap: 8px;
        padding: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(135, 35, 65, 0.2) transparent;
    }

    .settings-tabs-header::-webkit-scrollbar {
        height: 4px;
    }

    .settings-tabs-header::-webkit-scrollbar-track {
        background: transparent;
    }

    .settings-tabs-header::-webkit-scrollbar-thumb {
        background: rgba(135, 35, 65, 0.2);
        border-radius: 2px;
    }

    .settings-tabs-header::-webkit-scrollbar-thumb:hover {
        background: rgba(135, 35, 65, 0.4);
    }

    .settings-tab-btn {
        flex: 0 0 auto;
        padding: 12px 20px;
        border-radius: 12px;
        border: 2px solid transparent;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .settings-tab-btn i {
        font-size: 18px;
    }

    .settings-tab-btn:hover {
        background: rgba(135, 35, 65, 0.1);
        color: #872341;
        transform: translateY(-2px);
    }

    .settings-tab-btn.active {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }

    .settings-tab-content {
        display: none;
        padding: 28px;
    }

    .settings-tab-content.active {
        display: block;
    }

    /* Desktop: All tabs visible horizontally */
    @media (min-width: 769px) {
        .settings-tabs-header {
            overflow-x: visible;
        }
        
        .settings-tab-btn {
            flex: 1;
            justify-content: center;
        }
    }

    /* Tablet: Icons hidden, horizontal scroll */
    @media (max-width: 768px) and (min-width: 641px) {
        .settings-tab-btn i {
            display: none;
        }
        
        .schedule-grid {
            grid-template-columns: 1fr;
        }
        
        .time-inputs {
            grid-template-columns: 1fr;
        }
    }

    /* Mobile: Compact layout */
    @media (max-width: 640px) {
        .settings-tab-btn i {
            display: none;
        }
        
        .settings-tab-btn {
            padding: 10px 16px;
            font-size: 13px;
        }

        .schedule-grid {
            grid-template-columns: 1fr;
        }
        
        .time-inputs {
            grid-template-columns: 1fr;
        }

        .settings-tab-content {
            padding: 16px;
        }
    }
</style>

<div class="settings-header">
    <h1 class="settings-title">
        <i class="bi bi-gear-fill me-2"></i>Settings
    </h1>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="bi bi-check-circle-fill" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Settings Tabs Navigation -->
<div class="settings-tabs-container">
    <div class="settings-tabs-header">
        <button class="settings-tab-btn active" data-tab="schedule" onclick="switchTab(event, 'schedule')">
            <i class="bi bi-calendar-week"></i>
            <span>Schedule</span>
        </button>
        <button class="settings-tab-btn" data-tab="profile" onclick="switchTab(event, 'profile')">
            <i class="bi bi-person-circle"></i>
            <span>Profile</span>
        </button>
        <button class="settings-tab-btn" data-tab="social" onclick="switchTab(event, 'social')">
            <i class="bi bi-share"></i>
            <span>Social</span>
        </button>
    </div>
</div>

<!-- Schedule Tab -->
<div id="schedule" class="settings-tab-content active">
        <form action="{{ route('provider.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="section-title">
                <i class="bi bi-clock-history"></i>
                Set Your Working Hours for Each Day
            </div>
            
            <div class="schedule-grid">
                @php
                    $days = [
                        ['name' => 'Sunday', 'index' => 0],
                        ['name' => 'Monday', 'index' => 1],
                        ['name' => 'Tuesday', 'index' => 2],
                        ['name' => 'Wednesday', 'index' => 3],
                        ['name' => 'Thursday', 'index' => 4],
                        ['name' => 'Friday', 'index' => 5],
                        ['name' => 'Saturday', 'index' => 6],
                    ];
                    
                    // Get existing schedules
                    $existingSchedules = $provider->schedules->keyBy('weekday');
                @endphp
                
                @foreach($days as $day)
                    @php
                        $schedule = $existingSchedules->get($day['index']);
                        $isOff = $schedule ? $schedule->is_off : false;
                        $startTime = $schedule && !$isOff ? substr($schedule->start_time, 0, 5) : '09:00';
                        $endTime = $schedule && !$isOff ? substr($schedule->end_time, 0, 5) : '18:00';
                    @endphp
                    
                    <div class="schedule-day-card schedule-day-{{ $day['index'] }} {{ !$isOff ? 'active' : 'off' }}">
                        <input type="hidden" name="schedule[{{ $day['index'] }}][weekday]" value="{{ $day['index'] }}">
                        
                        <div class="day-header">
                            <span class="day-name">{{ $day['name'] }}</span>
                            <label class="day-toggle">
                                <input type="checkbox" 
                                       class="day-checkbox"
                                       name="schedule[{{ $day['index'] }}][enabled]" 
                                       value="1"
                                       data-day="{{ $day['index'] }}"
                                       {{ !$isOff ? 'checked' : '' }}
                                       onchange="toggleDay({{ $day['index'] }})">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="time-inputs time-inputs-{{ $day['index'] }}" style="{{ !$isOff ? '' : 'display: none;' }}">
                            
                            <div class="time-input-group">
                                <label class="time-input-label">Start Time</label>
                                <input type="time" 
                                       name="schedule[{{ $day['index'] }}][start_time]" 
                                       class="time-input"
                                       value="{{ $startTime }}">
                            </div>
                            
                            <div class="time-input-group">
                                <label class="time-input-label">End Time</label>
                                <input type="time" 
                                       name="schedule[{{ $day['index'] }}][end_time]" 
                                       class="time-input"
                                       value="{{ $endTime }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Break Time Configuration -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-cup-hot"></i>
                    Break Time Configuration
                </div>
                
                <div class="checkbox-custom mb-3">
                    <input type="checkbox" 
                           name="has_break" 
                           id="has_break" 
                           value="1"
                           onchange="toggleBreak()"
                           {{ $provider->break_start ? 'checked' : '' }}>
                    <label for="has_break">I take a break during work hours</label>
                </div>
                
                <div class="break-time-section" id="break-time-section" style="{{ $provider->break_start ? '' : 'display: none;' }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Break Start Time</label>
                            <input type="time" 
                                   name="break_start" 
                                   class="form-control-custom"
                                   value="{{ $provider->break_start ?? '13:00' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Break End Time</label>
                            <input type="time" 
                                   name="break_end" 
                                   class="form-control-custom"
                                   value="{{ $provider->break_end ?? '14:00' }}">
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #6b7280; margin-top: 12px; margin-bottom: 0;">
                        <i class="bi bi-info-circle me-1"></i>
                        Break time will be excluded from your available appointment slots
                    </p>
                </div>
            </div>
            
            <!-- Buffer Time Configuration -->
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-hourglass-split"></i>
                    Buffer Time Between Appointments
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Buffer Time (minutes)</label>
                        <select name="buffer_time" class="form-control-custom">
                            <option value="0" {{ ($provider->buffer_time ?? 0) == 0 ? 'selected' : '' }}>No buffer time</option>
                            <option value="5" {{ ($provider->buffer_time ?? 0) == 5 ? 'selected' : '' }}>5 minutes</option>
                            <option value="10" {{ ($provider->buffer_time ?? 0) == 10 ? 'selected' : '' }}>10 minutes</option>
                            <option value="15" {{ ($provider->buffer_time ?? 0) == 15 ? 'selected' : '' }}>15 minutes</option>
                            <option value="20" {{ ($provider->buffer_time ?? 0) == 20 ? 'selected' : '' }}>20 minutes</option>
                            <option value="30" {{ ($provider->buffer_time ?? 0) == 30 ? 'selected' : '' }}>30 minutes</option>
                        </select>
                        <p style="font-size: 13px; color: #6b7280; margin-top: 8px; margin-bottom: 0;">
                            <i class="bi bi-info-circle me-1"></i>
                            Buffer time is added between appointments for preparation and cleanup
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle"></i>
                    Save Schedule Settings
                </button>
            </div>
        </form>
</div>

<!-- Profile Tab -->
<div id="profile" class="settings-tab-content">
    <div class="settings-card">
        <form action="{{ route('provider.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-person-badge"></i>
                    Basic Information
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control-custom @error('name') border-danger @enderror"
                               value="{{ old('name', $provider->name) }}"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-custom">Email Address <span class="text-danger">*</span></label>
                        <input type="email" 
                               name="email" 
                               class="form-control-custom @error('email') border-danger @enderror"
                               value="{{ old('email', $provider->email) }}"
                               required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-custom">Phone Number</label>
                        <input type="tel" 
                               name="phone" 
                               class="form-control-custom"
                               value="{{ old('phone', $provider->phone) }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-custom">Expertise</label>
                        <input type="text" 
                               name="expertise" 
                               class="form-control-custom"
                               placeholder="e.g., Hair Stylist, Barber, Makeup Artist"
                               value="{{ old('expertise', $provider->expertise) }}">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label-custom">Bio / About Me</label>
                        <textarea name="bio" 
                                  class="form-control-custom" 
                                  rows="4"
                                  placeholder="Tell customers about your experience and specialties...">{{ old('bio', $provider->bio) }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-image"></i>
                    Profile Photo
                </div>
                
                <div class="d-flex align-items-center gap-4">
                    <div>
                        @if($provider->photo)
                            <img src="{{ asset('storage/' . $provider->photo) }}" 
                                 alt="{{ $provider->name }}" 
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #e5e7eb;">
                        @else
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144); display: flex; align-items: center; justify-content: center; font-size: 40px; color: #fff; font-weight: 700;">
                                {{ strtoupper(substr($provider->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label-custom">Upload New Photo</label>
                        <input type="file" 
                               name="photo" 
                               class="form-control-custom"
                               accept="image/*">
                        <p style="font-size: 13px; color: #6b7280; margin-top: 8px; margin-bottom: 0;">
                            <i class="bi bi-info-circle me-1"></i>
                            Recommended: Square image, at least 400x400px
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-shield-lock"></i>
                    Change Password
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Current Password</label>
                        <input type="password" 
                               name="current_password" 
                               class="form-control-custom @error('current_password') border-danger @enderror"
                               placeholder="Enter current password">
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6"></div>
                    
                    <div class="col-md-6">
                        <label class="form-label-custom">New Password</label>
                        <input type="password" 
                               name="password" 
                               class="form-control-custom @error('password') border-danger @enderror"
                               placeholder="Enter new password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-custom">Confirm New Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-control-custom"
                               placeholder="Confirm new password">
                    </div>
                </div>
                
                <p style="font-size: 13px; color: #6b7280; margin-top: 12px; margin-bottom: 0;">
                    <i class="bi bi-info-circle me-1"></i>
                    Leave password fields empty if you don't want to change it
                </p>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle"></i>
                    Update Profile Information
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Social Tab -->
<div id="social" class="settings-tab-content">
    <div class="settings-card">
        <form action="{{ route('provider.settings.update-social') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="section-title">
                <i class="bi bi-link-45deg"></i>
                Connect Your Social Profiles
            </div>
            
            <div class="social-input-group">
                <div class="social-icon facebook">
                    <i class="bi bi-facebook"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">Facebook Profile</label>
                    <input type="url" 
                           name="facebook" 
                           class="form-control-custom"
                           placeholder="https://facebook.com/yourprofile"
                           value="{{ old('facebook', $provider->facebook ?? '') }}">
                </div>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon instagram">
                    <i class="bi bi-instagram"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">Instagram Profile</label>
                    <input type="url" 
                           name="instagram" 
                           class="form-control-custom"
                           placeholder="https://instagram.com/yourprofile"
                           value="{{ old('instagram', $provider->instagram ?? '') }}">
                </div>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon twitter">
                    <i class="bi bi-twitter"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">Twitter Profile</label>
                    <input type="url" 
                           name="twitter" 
                           class="form-control-custom"
                           placeholder="https://twitter.com/yourprofile"
                           value="{{ old('twitter', $provider->twitter ?? '') }}">
                </div>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon youtube">
                    <i class="bi bi-youtube"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">YouTube Channel</label>
                    <input type="url" 
                           name="youtube" 
                           class="form-control-custom"
                           placeholder="https://youtube.com/yourchannel"
                           value="{{ old('youtube', $provider->youtube ?? '') }}">
                </div>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon linkedin">
                    <i class="bi bi-linkedin"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">LinkedIn Profile</label>
                    <input type="url" 
                           name="linkedin" 
                           class="form-control-custom"
                           placeholder="https://linkedin.com/in/yourprofile"
                           value="{{ old('linkedin', $provider->linkedin ?? '') }}">
                </div>
            </div>
            
            <div class="social-input-group">
                <div class="social-icon website">
                    <i class="bi bi-globe"></i>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label-custom">Personal Website</label>
                    <input type="url" 
                           name="website" 
                           class="form-control-custom"
                           placeholder="https://yourwebsite.com"
                           value="{{ old('website', $provider->website ?? '') }}">
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle"></i>
                    Save Social Links
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
// Tab switching functionality
function switchTab(event, tabName) {
    event.preventDefault();
    
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.settings-tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all buttons
    const tabButtons = document.querySelectorAll('.settings-tab-btn');
    tabButtons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active class to clicked button
    event.target.closest('.settings-tab-btn').classList.add('active');
}

// Toggle day schedule visibility
function toggleDay(dayIndex) {
    const checkbox = document.querySelector('.day-checkbox[data-day="' + dayIndex + '"]');
    const timeInputs = document.querySelector('.time-inputs-' + dayIndex);
    
    if (checkbox && timeInputs) {
        if (checkbox.checked) {
            timeInputs.style.display = '';
        } else {
            timeInputs.style.display = 'none';
        }
    }
}

// Toggle break time section
function toggleBreak() {
    const checkbox = document.getElementById('has_break');
    const breakSection = document.getElementById('break-time-section');
    
    if (checkbox && breakSection) {
        if (checkbox.checked) {
            breakSection.style.display = '';
        } else {
            breakSection.style.display = 'none';
        }
    }
}
</script>
@endpush
