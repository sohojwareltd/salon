<x-customer-dashboard title="Settings">
<style>
    .settings-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    .settings-header::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }
    .settings-header::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        bottom: -50px;
        left: -50px;
    }
    .settings-card {
        background: white;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    .settings-card:hover {
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }
    .section-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }
    .form-group-settings {
        margin-bottom: 20px;
    }
    .form-label-settings {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-control-settings {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .form-control-settings:focus {
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
        outline: none;
    }
    .btn-save-settings {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-save-settings:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(135, 35, 65, 0.3);
        color: white;
    }
    .switch-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }
    .switch-card:hover {
        background: #f1f5f9;
    }
    .form-check-input:checked {
        background-color: #872341;
        border-color: #872341;
    }
    .danger-card {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 2px solid #fecaca;
        border-radius: 20px;
        padding: 24px;
    }
    .btn-danger-action {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-danger-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(220, 38, 38, 0.3);
        color: white;
    }
</style>

<!-- Page Header -->
<div class="settings-header">
    <div style="position: relative; z-index: 1;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
            <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-gear-fill" style="font-size: 28px; color: white;"></i>
            </div>
            <div>
                <h2 style="font-size: 32px; font-weight: 800; color: white; margin: 0;">
                    Settings
                </h2>
                <p style="font-size: 15px; color: rgba(255,255,255,0.9); margin: 0;">
                    Manage your account preferences and security
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Settings -->
    <div class="col-lg-8">
        <div class="settings-card">
            <div class="section-title">
                <div class="section-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Profile Settings
                    </h5>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        Update your personal information
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group-settings">
                    <label for="name" class="form-label-settings">
                        <i class="bi bi-person-fill" style="color: #872341;"></i>Full Name
                    </label>
                    <input type="text" class="form-control-settings @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           placeholder="Enter your full name">
                    @error('name')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-settings">
                    <label for="email" class="form-label-settings">
                        <i class="bi bi-envelope-fill" style="color: #872341;"></i>Email Address
                    </label>
                    <input type="email" class="form-control-settings @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                           placeholder="your.email@example.com">
                    @error('email')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-settings">
                    <label for="phone" class="form-label-settings">
                        <i class="bi bi-telephone-fill" style="color: #872341;"></i>Phone Number
                    </label>
                    <input type="tel" class="form-control-settings @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                           placeholder="+880 1XXX-XXXXXX">
                    @error('phone')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-settings">
                    <label for="address" class="form-label-settings">
                        <i class="bi bi-geo-alt-fill" style="color: #872341;"></i>Address
                    </label>
                    <textarea class="form-control-settings @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="4"
                              placeholder="Enter your complete address">{{ old('address', Auth::user()->address) }}</textarea>
                    @error('address')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-save-settings">
                    <i class="bi bi-check-circle-fill me-2"></i>Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="settings-card">
            <div class="section-title">
                <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Change Password
                    </h5>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        Keep your account secure
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group-settings">
                    <label for="current_password" class="form-label-settings">
                        <i class="bi bi-key-fill" style="color: #f59e0b;"></i>Current Password
                    </label>
                    <input type="password" class="form-control-settings @error('current_password') is-invalid @enderror" 
                           id="current_password" name="current_password" required
                           placeholder="Enter current password">
                    @error('current_password')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-settings">
                    <label for="password" class="form-label-settings">
                        <i class="bi bi-lock-fill" style="color: #f59e0b;"></i>New Password
                    </label>
                    <input type="password" class="form-control-settings @error('password') is-invalid @enderror" 
                           id="password" name="password" required
                           placeholder="Enter new password">
                    <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                        <i class="bi bi-info-circle me-1"></i>At least 8 characters
                    </small>
                    @error('password')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-settings">
                    <label for="password_confirmation" class="form-label-settings">
                        <i class="bi bi-shield-check" style="color: #f59e0b;"></i>Confirm New Password
                    </label>
                    <input type="password" class="form-control-settings" 
                           id="password_confirmation" name="password_confirmation" required
                           placeholder="Confirm new password">
                </div>

                <button type="submit" class="btn-save-settings" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="bi bi-shield-lock-fill me-2"></i>Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Notification Preferences & Account Settings -->
    <div class="col-lg-4">
        <!-- Notification Preferences -->
        <div class="settings-card">
            <div class="section-title">
                <div class="section-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Notifications
                    </h5>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.notifications.update') }}">
                @csrf
                @method('PUT')

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="email_notifications" 
                               name="email_notifications" value="1" checked style="cursor: pointer;">
                        <label class="form-check-label" for="email_notifications" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">Email Notifications</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                Receive appointment confirmations via email
                            </small>
                        </label>
                    </div>
                </div>

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sms_notifications" 
                               name="sms_notifications" value="1" style="cursor: pointer;">
                        <label class="form-check-label" for="sms_notifications" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">SMS Notifications</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                Get appointment reminders via SMS
                            </small>
                        </label>
                    </div>
                </div>

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="promotional_emails" 
                               name="promotional_emails" value="1" style="cursor: pointer;">
                        <label class="form-check-label" for="promotional_emails" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">Promotional Emails</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                Receive special offers and discounts
                            </small>
                        </label>
                    </div>
                </div>

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="reminder_notifications" 
                               name="reminder_notifications" value="1" checked style="cursor: pointer;">
                        <label class="form-check-label" for="reminder_notifications" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">Appointment Reminders</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                24 hours before appointments
                            </small>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-save-settings" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="bi bi-check-circle-fill me-2"></i>Save Preferences
                </button>
            </form>
        </div>

        <!-- Privacy Settings -->
        <div class="settings-card">
            <div class="section-title">
                <div class="section-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="bi bi-eye-slash"></i>
                </div>
                <div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Privacy
                    </h5>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.privacy.update') }}">
                @csrf
                @method('PUT')

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="profile_visibility" 
                               name="profile_visibility" value="1" checked style="cursor: pointer;">
                        <label class="form-check-label" for="profile_visibility" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">Profile Visibility</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                Allow salons to see your booking history
                            </small>
                        </label>
                    </div>
                </div>

                <div class="switch-card">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="show_reviews" 
                               name="show_reviews" value="1" checked style="cursor: pointer;">
                        <label class="form-check-label" for="show_reviews" style="cursor: pointer;">
                            <strong style="color: #1e293b; font-size: 14px;">Public Reviews</strong>
                            <small class="d-block" style="color: #64748b; font-size: 12px; margin-top: 2px;">
                                Display your name on reviews
                            </small>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-save-settings" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="bi bi-check-circle-fill me-2"></i>Save Privacy Settings
                </button>
            </form>
        </div>

        <!-- Account Actions -->
        <div class="danger-card">
            <div class="section-title" style="border-bottom-color: #fecaca;">
                <div class="section-icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #991b1b; margin: 0;">
                        Danger Zone
                    </h5>
                </div>
            </div>
            
            <div style="background: white; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    <div style="flex-shrink: 0;">
                        <i class="bi bi-info-circle-fill" style="color: #dc2626; font-size: 20px;"></i>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: #991b1b; margin: 0; line-height: 1.5;">
                            Once you delete your account, there is no going back. Please be certain.
                        </p>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn-danger-action" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <i class="bi bi-trash me-2"></i>Delete Account
            </button>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i> Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you absolutely sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-danger fw-semibold">All your data including:</p>
                <ul class="text-muted">
                    <li>Appointment history</li>
                    <li>Reviews</li>
                    <li>Payment records</li>
                    <li>Personal information</li>
                </ul>
                <p class="text-muted">will be permanently deleted.</p>
                
                <form method="POST" action="{{ route('customer.account.delete') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-2"></i> Yes, Delete My Account
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
</x-customer-dashboard>
