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

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 12px;
            left: 12px;
            right: 12px;
            background: white;
            padding: 8px 12px;
            z-index: 1000;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
            border-radius: 30px;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            color: #6b7280;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            cursor: pointer;
            position: relative;
        }

        .bottom-nav-item i {
            font-size: 22px;
        }

        .bottom-nav-item.active {
            color: var(--primary);
        }

        .bottom-nav-item:hover {
            color: var(--primary);
        }

        .bottom-nav-item .badge {
            position: absolute;
            top: 4px;
            right: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 16px;
            text-align: center;
        }

        /* Bootstrap Offcanvas Customization */
        .offcanvas {
            border-radius: 24px 0 0 24px;
        }

        .offcanvas-header {
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .offcanvas-body {
            padding: 20px;
        }
        
        .offcanvas-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .offcanvas-body::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .offcanvas-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dashboard-offcanvas-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            color: #374151;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: 500;
        }

        .dashboard-offcanvas-link:hover {
            background: linear-gradient(135deg, rgba(135, 35, 65, 0.05), rgba(190, 49, 68, 0.05));
            color: var(--primary);
            transform: translateX(4px);
        }

        .dashboard-offcanvas-link.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .dashboard-offcanvas-link i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .offcanvas-logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 16px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 16px;
        }

        .offcanvas-logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-sidebar {
                display: none !important;
            }

            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
                width: 89%;
                margin: 0 auto;
            }

            .top-nav {
                flex-direction: row;
                padding: 12px 16px;
                margin-bottom: 16px;
            }

            .top-nav > div:first-child {
                flex: 1;
            }

            .top-nav .user-menu {
                gap: 8px;
            }

            .top-nav .user-menu > a {
                display: none;
            }

            .page-title {
                font-size: 20px;
            }

            .dashboard-content {
                padding: 16px;
                margin-bottom: 80px;
            }

            body {
                padding-bottom: 80px;
            }

            .container-fluid {
                padding: 12px !important;
            }
        }

        @media (max-width: 576px) {
            .top-nav > div:first-child > div:first-child {
                display: none;
            }

            .page-title {
                font-size: 18px;
            }

            .top-nav > div:first-child > div > p {
                display: none;
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

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        {{-- <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a> --}}
        <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('customer.bookings') }}" class="bottom-nav-item {{ request()->routeIs('customer.bookings') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i>
            <span>Bookings</span>
        </a>
        <a href="{{ route('customer.notifications') }}" class="bottom-nav-item {{ request()->routeIs('customer.notifications') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i>
            <span>Alerts</span>
            @if($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
            @endif
        </a>
        <button class="bottom-nav-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboardOffcanvas" aria-controls="dashboardOffcanvas">
            <i class="bi bi-list"></i>
            <span>Menu</span>
        </button>
    </nav>

    <!-- Dashboard Offcanvas Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="dashboardOffcanvas" aria-labelledby="dashboardOffcanvasLabel" data-bs-scroll="false" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center gap-3" id="dashboardOffcanvasLabel">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-scissors" style="font-size: 20px; color: white;"></i>
                </div>
                <div>
                    <h6 style="margin: 0; font-weight: 700; color: var(--dark);">Saloon</h6>
                    <p style="margin: 0; font-size: 11px; color: #64748b;">Dashboard Menu</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- User Info -->
            <div style="display: flex; align-items: center; gap: 16px; padding: 20px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 16px; margin-bottom: 24px; color: white;">
                <div style="width: 56px; height: 56px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 18px; font-weight: 700; margin-bottom: 4px;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 13px; opacity: 0.9; background: rgba(255, 255, 255, 0.2); padding: 4px 12px; border-radius: 20px; display: inline-block;">Customer</div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div style="margin-bottom: 24px;">
                <h6 style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 12px; padding-left: 4px;">
                    Dashboard
                </h6>
                <a href="{{ route('customer.dashboard') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('customer.bookings') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.bookings') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span>My Bookings</span>
                </a>
                <a href="{{ route('customer.notifications') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.notifications') ? 'active' : '' }}" style="position: relative;">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifications</span>
                    @if($unreadCount > 0)
                    <span style="position: absolute; right: 16px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; min-width: 20px; text-align: center;">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </a>
                <a href="{{ route('providers.index') }}" class="dashboard-offcanvas-link {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                    <i class="bi bi-search"></i>
                    <span>Find Salons</span>
                </a>
            </div>

            <!-- Account Section -->
            <div style="margin-bottom: 24px;">
                <h6 style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 12px; padding-left: 4px;">
                    Account
                </h6>
                <a href="{{ route('customer.payments') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.payments') ? 'active' : '' }}">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Payments</span>
                </a>
                <a href="{{ route('customer.profile') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-fill"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('customer.settings') }}" class="dashboard-offcanvas-link {{ request()->routeIs('customer.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Settings</span>
                </a>
            </div>

            <!-- Logout -->
            <div style="padding-top: 16px; border-top: 2px solid #f1f5f9;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="offcanvas-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto close offcanvas when clicking navigation links
        const offcanvasElement = document.getElementById('dashboardOffcanvas');
        if (offcanvasElement) {
            const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
            
            offcanvasElement.querySelectorAll('.dashboard-offcanvas-link').forEach(link => {
                link.addEventListener('click', () => {
                    bsOffcanvas.hide();
                });
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
