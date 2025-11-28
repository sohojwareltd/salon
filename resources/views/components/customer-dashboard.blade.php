@php
    $notifications = auth()->user()->notifications()->limit(10)->get();
    $unreadCount = auth()->user()->unreadNotificationsCount();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Customer Dashboard' }} - Saloon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/saloon-theme.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #872341;
            --secondary: #BE3144;
            --accent: #E17564;
            --dark: #09122C;
            --light-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .dashboard-sidebar {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            color: white;
        }

        .sidebar-brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .sidebar-brand-text h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #fff;
        }

        .sidebar-brand-text p {
            font-size: 12px;
            margin: 0;
            opacity: 0.9;
            color: #fff
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-link-item i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .nav-link-item:hover {
            background: linear-gradient(135deg, rgba(135, 35, 65, 0.1), rgba(190, 49, 68, 0.1));
            color: var(--primary);
            transform: translateX(4px);
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 8px 16px rgba(135, 35, 65, 0.3);
        }

        .nav-link-item.active:hover {
            transform: translateX(0);
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            border-radius: 20px;
            padding: 16px 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .btn-logout {
            padding: 10px 20px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }

        /* Content Area */
        .dashboard-content {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            min-height: 600px;
        }

        /* Alert Styles */
        .alert-modern {
            border-radius: 16px;
            border: none;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: start;
            gap: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-modern i {
            font-size: 24px;
            margin-top: 2px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-sidebar {
                position: static;
                margin-bottom: 24px;
            }

            .top-nav {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .page-title {
                font-size: 22px;
            }

            .dashboard-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="padding: 24px; max-width: 1600px;">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="dashboard-sidebar">
                    <!-- Salon Brand Logo -->
                    <div style="text-align: center; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #f1f5f9;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: linear-gradient(135deg, #872341, #BE3144); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(135, 35, 65, 0.3);">
                            <i class="bi bi-scissors" style="font-size: 40px; color: white;"></i>
                        </div>
                        <h3 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">
                            Saloon
                        </h3>
                        <p style="font-size: 12px; color: #64748b; margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                            Premium Beauty Services
                        </p>
                    </div>

                    <!-- User Info -->
                    <div class="sidebar-brand">
                        <div class="sidebar-brand-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="sidebar-brand-text">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>Customer Dashboard</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav>
                        <a href="{{ route('customer.dashboard') }}" class="nav-link-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customer.bookings') }}" class="nav-link-item {{ request()->routeIs('customer.bookings') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>My Bookings</span>
                        </a>
                        <a href="{{ route('customer.notifications') }}" class="nav-link-item {{ request()->routeIs('customer.notifications') ? 'active' : '' }}" style="position: relative;">
                            <i class="bi bi-bell-fill"></i>
                            <span>Notifications</span>
                            {{-- Notification Badge --}}
                            @if($unreadCount > 0)
                            <span style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; min-width: 20px; text-align: center; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);">
                                {{ $unreadCount }}
                            </span>
                            @endif
                        </a>
                        <a href="{{ route('providers.index') }}" class="nav-link-item {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                            <i class="bi bi-search"></i>
                            <span>Find Salons</span>
                        </a>
                        <a href="{{ route('customer.payments') }}" class="nav-link-item {{ request()->routeIs('customer.payments') ? 'active' : '' }}">
                            <i class="bi bi-credit-card-fill"></i>
                            <span>Payments</span>
                        </a>
                        <a href="{{ route('customer.profile') }}" class="nav-link-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-fill"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('customer.settings') }}" class="nav-link-item {{ request()->routeIs('customer.settings') ? 'active' : '' }}">
                            <i class="bi bi-gear-fill"></i>
                            <span>Settings</span>
                        </a>
                    </nav>

                    <!-- Logout Button -->
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid #f1f5f9;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout w-100">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Top Navigation Bar -->
                <div class="top-nav">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #872341, #BE3144); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(135, 35, 65, 0.2);">
                            <i class="bi bi-scissors" style="font-size: 24px; color: white;"></i>
                        </div>
                        <div>
                            <h1 class="page-title" style="margin-bottom: 0;">{{ $title ?? 'Dashboard' }}</h1>
                            <p style="font-size: 12px; color: #64748b; margin: 0; font-weight: 600;">Welcome back to Saloon</p>
                        </div>
                    </div>
                    <div class="user-menu">
                        <a href="{{ route('providers.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                            <i class="bi bi-plus-circle me-2"></i>Book Now
                        </a>
                        
                        {{-- Notification Bell --}}
                        <x-notification-bell :notifications="$notifications" :unreadCount="$unreadCount" />
                        
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="dashboard-content">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert-modern alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <strong>Success!</strong>
                                <p style="margin: 4px 0 0 0;">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-modern alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <strong>Error!</strong>
                                <p style="margin: 4px 0 0 0;">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert-modern alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <strong>Info</strong>
                                <p style="margin: 4px 0 0 0;">{{ session('info') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // No additional scripts needed - notification bell component handles its own JS
    </script>
    
    @stack('scripts')
</body>
</html>
