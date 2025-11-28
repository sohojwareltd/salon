<x-customer-dashboard title="My Profile">
<style>
    .profile-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 24px;
        padding: 48px 40px;
        margin-bottom: 32px;
        box-shadow: 0 20px 60px rgba(135, 35, 65, 0.3);
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 800;
        color: #872341;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        margin: 0 auto 20px;
    }

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .form-group-modern {
        margin-bottom: 24px;
    }

    .form-label-modern {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s;
        background: #f8fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #872341;
        background: white;
        box-shadow: 0 0 0 4px rgba(135, 35, 65, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(135, 35, 65, 0.3);
    }

    .stat-box {
        background: linear-gradient(135deg, rgba(135, 35, 65, 0.1), rgba(190, 49, 68, 0.1));
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(135, 35, 65, 0.15);
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #872341;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<!-- Profile Header -->
<div class="profile-header" style="position: relative; z-index: 1;">
    <div style="position: relative; z-index: 1; text-align: center;">
        <div class="profile-avatar">
            {{ strtoupper(substr($customer->name, 0, 1)) }}
        </div>
        <h2 style="font-size: 32px; font-weight: 800; color: white; margin-bottom: 8px;">
            {{ $customer->name }}
        </h2>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.9); margin-bottom: 0;">
            <i class="bi bi-envelope-fill me-2"></i>{{ $customer->email }}
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="info-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #f1f5f9;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #872341, #BE3144); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person-circle" style="font-size: 24px; color: white;"></i>
                </div>
                <div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Profile Information
                    </h5>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        Update your personal details
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group-modern">
                    <label for="name" class="form-label-modern">
                        <i class="bi bi-person-fill me-1" style="color: #872341;"></i>Full Name
                    </label>
                    <input type="text" class="form-control-modern @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $customer->name) }}" required
                           placeholder="Enter your full name">
                    @error('name')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label for="email" class="form-label-modern">
                        <i class="bi bi-envelope-fill me-1" style="color: #872341;"></i>Email Address
                    </label>
                    <input type="email" class="form-control-modern @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $customer->email) }}" required
                           placeholder="your.email@example.com">
                    @error('email')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label for="phone" class="form-label-modern">
                        <i class="bi bi-telephone-fill me-1" style="color: #872341;"></i>Phone Number
                    </label>
                    <input type="tel" class="form-control-modern @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" 
                           placeholder="+880 1XXX-XXXXXX">
                    @error('phone')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label for="address" class="form-label-modern">
                        <i class="bi bi-geo-alt-fill me-1" style="color: #872341;"></i>Address
                    </label>
                    <textarea class="form-control-modern @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="4" 
                              placeholder="Enter your complete address">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle-fill me-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Stats/Additional Info -->
    <div class="col-lg-4">
        <!-- Account Stats -->
        <div class="info-card" style="margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #872341, #BE3144); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-star-fill" style="font-size: 20px; color: white;"></i>
                </div>
                <div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Account Stats
                    </h5>
                </div>
            </div>

            <div class="stat-box" style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0 0 4px 0; font-weight: 500;">
                            Total Bookings
                        </p>
                        <h3 style="font-size: 32px; font-weight: 800; color: white; margin: 0;">
                            {{ $customer->appointments()->count() }}
                        </h3>
                    </div>
                    <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-calendar-check-fill" style="font-size: 28px; color: white;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-box" style="margin-bottom: 16px; background: linear-gradient(135deg, #10b981, #059669) !important;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0 0 4px 0; font-weight: 500;">
                            Completed
                        </p>
                        <h3 style="font-size: 32px; font-weight: 800; color: white; margin: 0;">
                            {{ $customer->appointments()->where('status', 'completed')->count() }}
                        </h3>
                    </div>
                    <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-check-circle-fill" style="font-size: 28px; color: white;"></i>
                    </div>
                </div>
            </div>

            <div class="stat-box" style="background: linear-gradient(135deg, #6366f1, #4f46e5) !important;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0 0 4px 0; font-weight: 500;">
                            Member Since
                        </p>
                        <h4 style="font-size: 20px; font-weight: 700; color: white; margin: 0;">
                            {{ $customer->created_at->format('M Y') }}
                        </h4>
                    </div>
                    <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clock-history" style="font-size: 28px; color: white;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="info-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-info-circle-fill" style="font-size: 20px; color: white;"></i>
                </div>
                <div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Account Details
                    </h5>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-calendar-event" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <p style="font-size: 12px; color: #78350f; margin: 0; font-weight: 600;">
                            Member Since
                        </p>
                        <p style="font-size: 16px; color: #78350f; margin: 0; font-weight: 700;">
                            {{ $customer->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(135deg, #ddd6fe, #c4b5fd); border-radius: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-shield-check" style="font-size: 22px; color: white;"></i>
                    </div>
                    <div>
                        <p style="font-size: 12px; color: #4c1d95; margin: 0; font-weight: 600;">
                            Account Type
                        </p>
                        <p style="font-size: 16px; color: #4c1d95; margin: 0; font-weight: 700;">
                            {{ ucfirst($customer->role) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-customer-dashboard>
