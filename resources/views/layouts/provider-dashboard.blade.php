@php
    $notifications = auth()->user()->notifications()->limit(10)->get();
    $unreadCount = auth()->user()->unreadNotificationsCount();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Provider Dashboard') - {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #09122C;
            --primary-1: #872341;
            --primary-2: #BE3144;
            --primary-3: #E17564;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        .provider-dashboard-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Dark Sidebar */
        .provider-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark) 0%, #0d1936 100%);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .provider-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .provider-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        /* Sidebar Header - Cover Image */
        .sidebar-cover {
            position: relative;
            height: 98px;
            background: linear-gradient(135deg, var(--primary-1) 0%, var(--primary-2) 100%);
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .sidebar-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(9, 18, 44, 0.8) 100%);
        }

        .sidebar-cover-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.1;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.05) 10px, rgba(255,255,255,.05) 20px);
        }

        /* Salon Logo */
        .provider-info {
            position: relative;
            padding: 20px;
            margin-top: -75px;
            z-index: 2;
        }

        .salon-logo-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background: white;
            padding: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .salon-logo {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary-2), var(--primary-3));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            font-weight: 600;
        }

        .salon-logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .salon-name {
            text-align: center;
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .salon-role {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            font-weight: 400;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 20px 16px;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link-item i {
            font-size: 18px;
            width: 24px;
            margin-right: 12px;
        }

        .nav-link-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link-item.active {
            background: linear-gradient(135deg, var(--primary-1), var(--primary-2));
            color: white;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
        }

        /* Provider Avatar in Bottom */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .provider-avatar-section {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .provider-avatar-section:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .provider-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-2), var(--primary-3));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .provider-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .provider-details {
            flex: 1;
            min-width: 0;
        }

        .provider-name {
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .provider-role-badge {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 400;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        .provider-header {
            background: linear-gradient(135deg, var(--primary-dark), #0d1936);
            padding: 24px 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-notification {
            position: relative;
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header-notification:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .header-notification i {
            font-size: 20px;
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 18px;
            height: 18px;
            background: #DC3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            color: white;
            border: 2px solid var(--primary-dark);
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header-user:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .header-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-2), var(--primary-3));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
            font-weight: 600;
        }

        .header-user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-user-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        .content-wrapper {
            padding: 32px 40px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .provider-sidebar {
                transform: translateX(-100%);
            }

            .provider-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .provider-header {
                padding: 16px 20px;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .header-title h1 {
                font-size: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $provider = auth()->user()->provider ?? null;
        $salon = $provider ? $provider->salon : null;
        $stats = [
            'pending_appointments' => $provider ? $provider->appointments()->where('status', 'pending')->count() : 0,
        ];
    @endphp

    <div class="provider-dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="provider-sidebar" id="providerSidebar">
            <!-- Cover Image -->
            <div class="sidebar-cover" style="background-image: url('{{ $salon && $salon->cover_image ? asset('storage/' . $salon->cover_image) : '' }}');">
                <div class="sidebar-cover-pattern"></div>
            </div>

            <!-- Salon Info -->
            <div class="provider-info">
                <div class="salon-logo-wrapper">
                    <div class="salon-logo">
                        @if($salon && $salon->logo)
                            <img src="{{ asset('storage/' . $salon->logo) }}" alt="{{ $salon->name }}">
                        @else
                            {{ $salon ? strtoupper(substr($salon->name, 0, 2)) : 'SL' }}
                        @endif
                    </div>
                </div>
                <div class="salon-name">{{ $salon ? $salon->name : 'Salon' }}</div>
                <div class="salon-role">Provider Dashboard</div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <a href="{{ route('provider.dashboard') }}" class="nav-link-item {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('provider.bookings.index') }}" class="nav-link-item {{ request()->routeIs('provider.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Appointments
                </a>
                <a href="{{ route('provider.services.index') }}" class="nav-link-item {{ request()->routeIs('provider.services.*') ? 'active' : '' }}">
                    <i class="bi bi-scissors"></i> Services
                </a>
                <a href="{{ route('provider.wallet.index') }}" class="nav-link-item {{ request()->routeIs('provider.wallet.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> Wallet
                </a>
                <a href="{{ route('provider.reviews.index') }}" class="nav-link-item {{ request()->routeIs('provider.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i> Reviews
                </a>
                @if($unreadCount > 0)
                <a href="/provider-dashboard/notifications" class="nav-link-item {{ request()->routeIs('provider.notifications') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i> Notifications
                    <span style="margin-left: auto; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; min-width: 20px; text-align: center;">{{ $unreadCount }}</span>
                </a>
                @endif
                <a href="{{ route('provider.settings') }}" class="nav-link-item {{ request()->routeIs('provider.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </nav>

            <!-- Provider Avatar Footer -->
            <div class="sidebar-footer">
                <div class="provider-avatar-section">
                    <div class="provider-avatar">
                        @if($provider && $provider->photo)
                            <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}">
                        @else
                            {{ $provider ? strtoupper(substr($provider->name, 0, 2)) : 'PR' }}
                        @endif
                    </div>
                    <div class="provider-details">
                        <div class="provider-name">{{ $provider ? $provider->name : 'Provider' }}</div>
                        <div class="provider-role-badge">Provider</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="provider-header">
                <div class="header-content">
                    <div class="header-title">
                        <h1><i class="bi bi-grid-fill me-2"></i> @yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="header-actions">
                        <x-notification-bell :notifications="$notifications" :unreadCount="$unreadCount" />
                        <div class="dropdown">
                            <div class="header-user" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="header-user-avatar">
                                    @if($provider && $provider->photo)
                                        <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}">
                                    @else
                                        {{ $provider ? strtoupper(substr($provider->name, 0, 2)) : 'PR' }}
                                    @endif
                                </div>
                                <span class="header-user-name">{{ $provider ? $provider->name : 'Provider' }}</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('provider.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
