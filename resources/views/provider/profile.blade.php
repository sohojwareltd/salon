<x-provider-dashboard title="My Profile">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <!-- Header Banner -->
                <div class="bg-gradient" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); height: 120px;"></div>
                
                <div class="card-body">
                    <!-- Avatar Section -->
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-end" style="margin-top: -60px; margin-bottom: 20px;">
                        <div class="bg-white rounded-circle border border-4 border-white shadow-lg d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 120px; height: 120px; font-size: 2.5rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="ms-sm-3 mt-3 mt-sm-0 text-center text-sm-start">
                            <h3 class="fw-bold mb-1">{{ auth()->user()->name }}</h3>
                            <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                            <span class="badge bg-primary">Service Provider</span>
                        </div>
                    </div>

                    <!-- Edit Profile Form -->
                    <form action="{{ route('provider.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <h5 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bi bi-person me-2 text-primary"></i>
                            Personal Information
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                       class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                       placeholder="+880 1XXX-XXXXXX"
                                       class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', auth()->user()->specialization ?? '') }}" 
                                       placeholder="e.g., Hair Styling, Makeup Artist"
                                       class="form-control @error('specialization') is-invalid @enderror">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea name="bio" id="bio" rows="4" 
                                          placeholder="Tell customers about your expertise and experience..."
                                          class="form-control @error('bio') is-invalid @enderror">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Change -->
                        <h5 class="fw-bold mb-3 d-flex align-items-center pt-3 border-top">
                            <i class="bi bi-lock me-2 text-primary"></i>
                            Change Password
                        </h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" name="current_password" id="current_password" 
                                       placeholder="Leave blank to keep current password"
                                       class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password" 
                                       placeholder="Enter new password"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       placeholder="Confirm new password"
                                       class="form-control">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end pt-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-provider-dashboard>
