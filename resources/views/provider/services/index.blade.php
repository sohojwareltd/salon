@extends('layouts.provider-dashboard')

@section('title', 'My Services')

@section('content')
<style>
    .services-header {
        background: white;
        border-radius: 16px;
        padding: 24px 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .services-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .btn-add-service {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-add-service:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(135, 35, 65, 0.4);
        color: white;
    }

    .services-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .services-table {
        width: 100%;
        border-collapse: collapse;
    }

    .services-table thead {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .services-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    .services-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .services-table tbody tr:hover {
        background: #f9fafb;
    }

    .services-table tbody td {
        padding: 20px;
        color: #374151;
        font-size: 14px;
    }

    .service-name {
        font-weight: 600;
        color: #111827;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .service-category {
        font-size: 13px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .service-category i {
        font-size: 12px;
    }

    .price-badge {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        color: #065f46;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        display: inline-block;
    }

    .duration-badge {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-edit {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(30, 64, 175, 0.2);
    }

    .btn-delete {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(153, 27, 27, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 80px;
        color: #d1d5db;
        margin-bottom: 24px;
        display: block;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 15px;
        margin-bottom: 24px;
    }

    .alert-success-modern {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        color: #065f46;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success-modern i {
        font-size: 20px;
    }
</style>

<div class="container-fluid" style="padding: 32px;">
    <!-- Header -->
    <div class="services-header">
        <div>
            <h1><i class="bi bi-scissors me-2" style="color: #872341;"></i> My Services</h1>
            <p style="color: #6b7280; margin: 8px 0 0 0;">Manage your services, pricing, and availability</p>
        </div>
        <a href="{{ route('provider.services.create') }}" class="btn-add-service">
            <i class="bi bi-plus-circle"></i>
            Add New Service
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success-modern">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Services Table -->
    <div class="services-card">
        @if($services->count() > 0)
            <table class="services-table">
                <thead>
                    <tr>
                        <th>SERVICE</th>
                        <th>DURATION</th>
                        <th>PRICE</th>
                        <th>STATUS</th>
                        <th style="text-align: right;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>
                                <div class="service-name">{{ $service->name }}</div>
                                <div class="service-category">
                                    <i class="bi bi-tag"></i>
                                    {{ $service->category ?? 'Uncategorized' }}
                                </div>
                            </td>
                            <td>
                                <span class="duration-badge">
                                    <i class="bi bi-clock"></i>
                                    {{ $service->duration }} min
                                </span>
                            </td>
                            <td>
                                <span class="price-badge">{{ App\Facades\Settings::formatPrice($service->price, false) }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('provider.services.edit', $service) }}" class="btn-action btn-edit" title="Edit Service">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('provider.services.destroy', $service) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete delete-btn" title="Delete Service">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="bi bi-scissors"></i>
                <h3>No Services Yet</h3>
                <p>Start by adding your first service to offer to customers</p>
                <a href="{{ route('provider.services.create') }}" class="btn-add-service" style="display: inline-flex;">
                    <i class="bi bi-plus-circle"></i>
                    Add Your First Service
                </a>
            </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Delete confirmation with SweetAlert2
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Delete Service?',
                text: "This action cannot be undone. The service will be permanently removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#872341',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'swal-gradient-popup',
                    title: 'swal-title',
                    confirmButton: 'swal-confirm-btn',
                    cancelButton: 'swal-cancel-btn'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<style>
    /* SweetAlert2 Custom Styling */
    .swal-gradient-popup {
        border-radius: 16px !important;
        padding: 32px !important;
    }
    
    .swal-title {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #111827 !important;
    }
    
    .swal-confirm-btn {
        background: linear-gradient(135deg, #872341, #BE3144) !important;
        color: white !important;
        padding: 12px 32px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        border: none !important;
        transition: all 0.3s ease !important;
    }
    
    .swal-confirm-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(135, 35, 65, 0.4) !important;
    }
    
    .swal-cancel-btn {
        background: #f3f4f6 !important;
        color: #6b7280 !important;
        padding: 12px 32px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        border: 2px solid #e5e7eb !important;
        transition: all 0.3s ease !important;
    }
    
    .swal-cancel-btn:hover {
        background: #e5e7eb !important;
        color: #374151 !important;
    }
</style>
@endsection
