@extends('layouts.provider-dashboard')

@section('title', 'Add New Service')

@section('content')
<style>
    .form-header {
        background: white;
        border-radius: 16px;
        padding: 24px 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .form-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 32px;
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
        font-family: inherit;
    }

    .form-control-modern:focus {
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
        outline: none;
    }

    .form-control-modern.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
        display: block;
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(135, 35, 65, 0.4);
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary-custom:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f0f0f0;
    }

    .input-group-modern {
        position: relative;
    }

    .input-prefix {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 15px;
        font-weight: 600;
        pointer-events: none;
    }

    .input-group-modern input {
        padding-left: 40px;
    }

    .form-text-modern {
        font-size: 13px;
        color: #6b7280;
        margin-top: 6px;
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
    }
</style>

<div class="container-fluid" style="padding: 32px;">
    <!-- Header -->
    <div class="form-header">
        <a href="{{ route('provider.services.index') }}" style="color: #6b7280; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
        <h1><i class="bi bi-plus-circle me-2" style="color: #872341;"></i> Add New Service</h1>
        <p style="color: #6b7280; margin: 0;">Create a new service to offer to your customers</p>
    </div>

    <!-- Form -->
    <div class="form-card">
        <form action="{{ route('provider.services.store') }}" method="POST">
            @csrf

            <!-- Service Name -->
            <div class="mb-4">
                <label for="name" class="form-label-modern">
                    <i class="bi bi-tag" style="color: #872341;"></i>
                    Service Name <span style="color: #dc3545;">*</span>
                </label>
                <input type="text" 
                       class="form-control-modern @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="e.g., Haircut, Beard Trim, Hair Color"
                       required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category" class="form-label-modern">
                    <i class="bi bi-grid" style="color: #872341;"></i>
                    Category
                </label>
                <select class="form-control-modern @error('category') is-invalid @enderror" 
                        id="category" 
                        name="category">
                    <option value="">Select a category</option>
                    <option value="Hair Care" {{ old('category') == 'Hair Care' ? 'selected' : '' }}>Hair Care</option>
                    <option value="Beard Care" {{ old('category') == 'Beard Care' ? 'selected' : '' }}>Beard Care</option>
                    <option value="Skin Care" {{ old('category') == 'Skin Care' ? 'selected' : '' }}>Skin Care</option>
                    <option value="Nail Care" {{ old('category') == 'Nail Care' ? 'selected' : '' }}>Nail Care</option>
                    <option value="Massage" {{ old('category') == 'Massage' ? 'selected' : '' }}>Massage</option>
                    <option value="Styling" {{ old('category') == 'Styling' ? 'selected' : '' }}>Styling</option>
                    <option value="Coloring" {{ old('category') == 'Coloring' ? 'selected' : '' }}>Coloring</option>
                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Duration and Price Row -->
            <div class="form-row mb-4">
                <!-- Duration -->
                <div>
                    <label for="duration" class="form-label-modern">
                        <i class="bi bi-clock" style="color: #872341;"></i>
                        Duration (minutes) <span style="color: #dc3545;">*</span>
                    </label>
                    <input type="number" 
                           class="form-control-modern @error('duration') is-invalid @enderror" 
                           id="duration" 
                           name="duration" 
                           value="{{ old('duration', 30) }}"
                           min="5"
                           max="480"
                           step="5"
                           placeholder="30"
                           required>
                    <small class="form-text-modern">How long does this service take?</small>
                    @error('duration')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="form-label-modern">
                        <i class="bi bi-currency-dollar" style="color: #872341;"></i>
                        Price ({{ App\Facades\Settings::currency() }}) <span style="color: #dc3545;">*</span>
                    </label>
                    <div class="input-group-modern">
                        <span class="input-prefix">{{ App\Facades\Settings::currency() }}</span>
                        <input type="number" 
                               class="form-control-modern @error('price') is-invalid @enderror" 
                               id="price" 
                               name="price" 
                               value="{{ old('price') }}"
                               min="0"
                               step="0.01"
                               placeholder="500"
                               required>
                    </div>
                    <small class="form-text-modern">Service price in BDT</small>
                    @error('price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label-modern">
                    <i class="bi bi-text-paragraph" style="color: #872341;"></i>
                    Description
                </label>
                <textarea class="form-control-modern @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          rows="4"
                          placeholder="Describe what's included in this service...">{{ old('description') }}</textarea>
                <small class="form-text-modern">Help customers understand what they'll get</small>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label-modern">
                    <i class="bi bi-toggle-on" style="color: #872341;"></i>
                    Status
                </label>
                <div style="background: #f9fafb; padding: 16px; border-radius: 12px; border: 2px solid #e5e7eb;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; margin: 0;">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               style="width: 20px; height: 20px; cursor: pointer;">
                        <div>
                            <div style="font-weight: 600; color: #111827;">Make this service active</div>
                            <small style="color: #6b7280;">Customers will be able to book this service</small>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-primary-custom">
                    <i class="bi bi-check-circle"></i>
                    Create Service
                </button>
                <a href="{{ route('provider.services.index') }}" class="btn-secondary-custom">
                    <i class="bi bi-x-circle"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
