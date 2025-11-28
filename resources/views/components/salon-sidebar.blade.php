<!-- Dark Sidebar Component -->
<aside class="salon-sidebar" id="salonSidebar">
    <!-- Cover Image -->
    <div class="sidebar-cover"
        @if ($salon->cover_image) style="background-image: url('{{ asset('storage/' . $salon->cover_image) }}');" @endif>
        <div class="sidebar-cover-pattern"></div>
    </div>

    <!-- Salon Logo & Info -->
    <div class="salon-info">
        <div class="salon-logo-wrapper">
            <div class="salon-logo">
                @if ($salon->logo)
                    <img src="{{ asset('storage/' . $salon->logo) }}" alt="{{ $salon->name }}">
                @else
                    {{ strtoupper(substr($salon->name, 0, 1)) }}
                @endif
            </div>
        </div>
        <h3 class="salon-name">{{ $salon->name }}</h3>
        <p class="salon-role">{{ $userRole ?? 'Salon Owner' }}</p>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section-title">Main Menu</div>
            <a href="{{ route('salon.dashboard') }}" class="nav-link-item {{ request()->routeIs('salon.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 nav-link-icon"></i>
                Dashboard
            </a>
            <a href="{{ route('salon.providers') }}" class="nav-link-item {{ request()->routeIs('salon.providers*') ? 'active' : '' }}">
                <i class="bi bi-people nav-link-icon"></i>
                Providers
            </a>
            <a href="{{ route('salon.bookings') }}" class="nav-link-item {{ request()->routeIs('salon.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check nav-link-icon"></i>
                Bookings
            </a>
            <a href="{{ route('salon.earnings') }}" class="nav-link-item {{ request()->routeIs('salon.earnings*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin nav-link-icon"></i>
                Earnings
            </a>

            <div class="nav-section-title">Manage</div>
            <a href="{{ route('salon.profile') }}" class="nav-link-item {{ request()->routeIs('salon.profile') ? 'active' : '' }}">
                <i class="bi bi-shop nav-link-icon"></i>
                Salon Profile
            </a>
            <a href="{{ route('salon.settings') }}" class="nav-link-item {{ request()->routeIs('salon.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear nav-link-icon"></i>
                Settings
            </a>
        
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-profile-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="user-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>
