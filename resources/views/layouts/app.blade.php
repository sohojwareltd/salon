<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Facades\Settings::get('site_name', config('app.name', 'Saloon')) }} - @yield('title', 'Welcome')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', App\Facades\Settings::get('meta_description', 'Professional salon booking and management system'))">
    <meta name="keywords" content="@yield('keywords', App\Facades\Settings::get('meta_keywords', 'salon, booking, appointment, beauty'))">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', App\Facades\Settings::get('meta_title', config('app.name')))">
    <meta property="og:description" content="@yield('description', App\Facades\Settings::get('meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(App\Facades\Settings::get('header_logo'))
        <meta property="og:image" content="{{ asset('storage/' . App\Facades\Settings::get('header_logo')) }}">
    @endif
    
    <!-- Favicon -->
    @if(App\Facades\Settings::get('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . App\Facades\Settings::get('favicon')) }}">
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Premium Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .offcanvas-backdrop {
            background: rgb(0 0 0 / 0%) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- ============================================
         PREMIUM HEADER - Multi-Vendor Saloon Marketplace
         ============================================ -->
    <header class="premium-header" id="mainHeader">
        <div class="header-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="header-logo">
                @if(App\Facades\Settings::get('header_logo'))
                    <img src="{{ asset('storage/' . App\Facades\Settings::get('header_logo')) }}" alt="{{ App\Facades\Settings::get('site_name', config('app.name')) }}" style="height: 100px;">
                @else
                    <div class="logo-icon">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <span>{{ App\Facades\Settings::get('site_name', config('app.name', 'Saloon')) }}</span>
                @endif
            </a>

            <!-- Navigation -->
            <nav class="header-nav" id="mainNav">
                @php
                    $mainMenu = App\Models\Menu::where('location', 'header')
                        ->where('is_active', true)
                        ->with('activeItems')
                        ->first();
                @endphp
                @if($mainMenu)
                    @foreach($mainMenu->activeItems as $menuItem)
                        <a href="{{ $menuItem->url }}" 
                           class="nav-link {{ request()->is(ltrim($menuItem->url, '/')) ? 'active' : '' }}"
                           target="{{ $menuItem->target }}">
                            @if($menuItem->icon)
                                <i class="{{ $menuItem->icon }}"></i>
                            @endif
                            {{ $menuItem->label }}
                        </a>
                    @endforeach
                @endif
            </nav>
         

            <!-- Header Actions -->
            <div class="header-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn-ghost">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        Register
                    </a>
                @else
                    @if(auth()->user()->role->name === 'customer')
                        <a href="{{ route('customer.dashboard') }}" class="btn-primary">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role->name === 'provider')
                        <a href="{{ route('provider.dashboard') }}" class="btn-primary">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role->name === 'salon')
                        <a href="{{ route('salon.dashboard') }}" class="btn-primary">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @else
                        <a href="/admin" class="btn-primary">
                            <i class="bi bi-shield-check"></i> Admin
                        </a>
                    @endif
                @endguest

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav" id="mobileBottomNav">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('services.index') }}" class="bottom-nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <i class="bi bi-scissors"></i>
            <span>Services</span>
        </a>
        <a href="{{ route('providers.index') }}" class="bottom-nav-item {{ request()->routeIs('providers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Providers</span>
        </a>
        {{-- @auth
            @if(auth()->user()->role->name === 'customer')
                <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            @elseif(auth()->user()->role->name === 'provider')
                <a href="{{ route('provider.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('provider.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        @endauth --}}
        <button class="bottom-nav-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvas" aria-controls="mobileOffcanvas">
            <i class="bi bi-list"></i>
            <span>Menu</span>
        </button>
    </nav>

    <!-- Mobile Offcanvas Menu (Bootstrap) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileOffcanvas" aria-labelledby="mobileOffcanvasLabel" data-bs-scroll="false" data-bs-backdrop="true">
        <div class="offcanvas-header" style="background: linear-gradient(135deg, #f9fafb, #f3f4f6); border-bottom: 1px solid #e5e7eb;">
            <div class="d-flex align-items-center gap-3" id="mobileOffcanvasLabel">
                @if(App\Facades\Settings::get('header_logo'))
                    <img src="{{ asset('storage/' . App\Facades\Settings::get('header_logo')) }}" alt="{{ App\Facades\Settings::get('site_name') }}" style="height: 40px;">
                @else
                    <i class="bi bi-scissors" style="font-size: 28px; color: #872341;"></i>
                    <span style="font-size: 20px; font-weight: 700; color: #872341;">{{ App\Facades\Settings::get('site_name', config('app.name')) }}</span>
                @endif
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" style="padding: 20px;">
            @guest
                <!-- Guest User Menu -->
                <div class="offcanvas-section">
                    <h6 class="offcanvas-section-title">Account</h6>
                    <a href="{{ route('login') }}" class="offcanvas-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="offcanvas-link">
                        <i class="bi bi-person-plus"></i>
                        <span>Register</span>
                    </a>
                </div>
            @else
                <!-- Authenticated User Info -->
                <div class="offcanvas-user-info">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ ucfirst(auth()->user()->role->name) }}</div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="offcanvas-section">
                    <h6 class="offcanvas-section-title">Account</h6>
                    @if(auth()->user()->role->name === 'customer')
                        <a href="{{ route('customer.dashboard') }}" class="offcanvas-link">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('customer.bookings') }}" class="offcanvas-link">
                            <i class="bi bi-calendar-check"></i>
                            <span>My Bookings</span>
                        </a>
                    @elseif(auth()->user()->role->name === 'provider')
                        <a href="{{ route('provider.dashboard') }}" class="offcanvas-link">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('provider.bookings') }}" class="offcanvas-link">
                            <i class="bi bi-calendar-event"></i>
                            <span>Appointments</span>
                        </a>
                    @elseif(auth()->user()->role->name === 'admin')
                        <a href="/admin" class="offcanvas-link">
                            <i class="bi bi-shield-check"></i>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                </div>
            @endguest

            <!-- Main Navigation -->
            <div class="offcanvas-section">
                <h6 class="offcanvas-section-title">Navigation</h6>
                @php
                    $mainMenu = App\Models\Menu::where('location', 'header')
                        ->where('is_active', true)
                        ->with('activeItems')
                        ->first();
                @endphp
                @if($mainMenu)
                    @foreach($mainMenu->activeItems as $menuItem)
                        <a href="{{ $menuItem->url }}" class="offcanvas-link" target="{{ $menuItem->target }}">
                            @if($menuItem->icon)
                                <i class="{{ $menuItem->icon }}"></i>
                            @else
                                <i class="bi bi-arrow-right"></i>
                            @endif
                            <span>{{ $menuItem->label }}</span>
                        </a>
                    @endforeach
                @endif
            </div>

            @auth
                <!-- Logout -->
                <div class="offcanvas-section">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="offcanvas-link offcanvas-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <!-- Page Content -->
    <main style="padding-top: 80px; padding-bottom: 80px; min-height: calc(100vh - 80px);">
        @yield('content')
    </main>

    <!-- ============================================
         PREMIUM FOOTER - Multi-Vendor Saloon Marketplace
         ============================================ -->
    <footer class="premium-footer">
        <div class="footer-top">
            <div class="footer-container">
                <div class="footer-grid">
                    <!-- Brand Column -->
                    <div class="footer-brand">
                        <div>
                            @if(App\Facades\Settings::get('header_logo'))
                                <img src="{{ asset('storage/' . App\Facades\Settings::get('header_logo')) }}" alt="{{ App\Facades\Settings::get('site_name') }}" style="height: 110px; margin-bottom: 1rem;">
                            @else
                                <h3 class="footer-logo">{{ App\Facades\Settings::get('site_name', config('app.name', 'Saloon')) }}</h3>
                            @endif
                            <p class="footer-description">
                                Your premier multi-vendor marketplace connecting customers with the finest salons, barbers, and beauty professionals. Experience luxury grooming at your fingertips.
                            </p>
                            @if(App\Facades\Settings::get('address'))
                                <p class="footer-description" style="margin-top: 1rem;">
                                    <i class="bi bi-geo-alt"></i> {{ App\Facades\Settings::get('address') }}
                                </p>
                            @endif
                            @if(App\Facades\Settings::get('phone'))
                                <p class="footer-description">
                                    <i class="bi bi-telephone"></i> {{ App\Facades\Settings::get('phone') }}
                                </p>
                            @endif
                            @if(App\Facades\Settings::get('email'))
                                <p class="footer-description">
                                    <i class="bi bi-envelope"></i> {{ App\Facades\Settings::get('email') }}
                                </p>
                            @endif
                        </div>
                        @if(App\Facades\Settings::get('facebook_url') || App\Facades\Settings::get('instagram_url') || App\Facades\Settings::get('twitter_url') || App\Facades\Settings::get('linkedin_url') || App\Facades\Settings::get('youtube_url'))
                        <div class="footer-social">
                            @if(App\Facades\Settings::get('facebook_url'))
                                <a href="{{ App\Facades\Settings::get('facebook_url') }}" target="_blank" class="social-link" aria-label="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                            @endif
                            @if(App\Facades\Settings::get('instagram_url'))
                                <a href="{{ App\Facades\Settings::get('instagram_url') }}" target="_blank" class="social-link" aria-label="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            @endif
                            @if(App\Facades\Settings::get('twitter_url'))
                                <a href="{{ App\Facades\Settings::get('twitter_url') }}" target="_blank" class="social-link" aria-label="Twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                            @endif
                            @if(App\Facades\Settings::get('linkedin_url'))
                                <a href="{{ App\Facades\Settings::get('linkedin_url') }}" target="_blank" class="social-link" aria-label="LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                            @endif
                            @if(App\Facades\Settings::get('youtube_url'))
                                <a href="{{ App\Facades\Settings::get('youtube_url') }}" target="_blank" class="social-link" aria-label="YouTube">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Footer Menu 1 -->
                    <div class="footer-section">
                        @php
                            $footerMenu1 = App\Models\Menu::where('location', 'footer_1')
                                ->where('is_active', true)
                                ->with('activeItems')
                                ->first();
                        @endphp
                        @if($footerMenu1)
                            <h4>{{ $footerMenu1->title }}</h4>
                            <ul class="footer-links">
                                @foreach($footerMenu1->activeItems as $menuItem)
                                    <li>
                                        <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}">
                                            @if($menuItem->icon)
                                                <i class="{{ $menuItem->icon }}"></i>
                                            @endif
                                            {{ $menuItem->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Footer Menu 2 -->
                    <div class="footer-section">
                        @php
                            $footerMenu2 = App\Models\Menu::where('location', 'footer_2')
                                ->where('is_active', true)
                                ->with('activeItems')
                                ->first();
                        @endphp
                        @if($footerMenu2)
                            <h4>{{ $footerMenu2->title }}</h4>
                            <ul class="footer-links">
                                @foreach($footerMenu2->activeItems as $menuItem)
                                    <li>
                                        <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}">
                                            @if($menuItem->icon)
                                                <i class="{{ $menuItem->icon }}"></i>
                                            @endif
                                            {{ $menuItem->label }}
                                        </a>
                                    </li>
                                @endforeach
                                <li>
                                    <a href="{{ route('faqs.index') }}">
                                        <i class="fas fa-question-circle"></i>
                                        FAQs
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>

                    <!-- Footer Menu 3 -->
                    <div class="footer-section">
                        @php
                            $footerMenu3 = App\Models\Menu::where('location', 'footer_3')
                                ->where('is_active', true)
                                ->with('activeItems')
                                ->first();
                        @endphp
                        @if($footerMenu3)
                            <h4>{{ $footerMenu3->title }}</h4>
                            <ul class="footer-links">
                                @foreach($footerMenu3->activeItems as $menuItem)
                                    <li>
                                        <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}">
                                            @if($menuItem->icon)
                                                <i class="{{ $menuItem->icon }}"></i>
                                            @endif
                                            {{ $menuItem->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Newsletter -->
                    <div class="footer-section">
                        <h4>Stay Connected</h4>
                        <p class="footer-description" style="margin-bottom: 1.5rem;">
                            Subscribe to get special offers, exclusive updates, and grooming tips.
                        </p>
                        <form class="newsletter-form" action="#" method="POST">
                            @csrf
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                class="newsletter-input"
                                required
                            >
                            <button type="submit" class="btn-newsletter">
                                <i class="bi bi-send-fill"></i> Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-container">
                <div class="footer-bottom-content">
                    <p class="footer-copyright">
                        &copy; {{ date('Y') }} {{ App\Facades\Settings::get('company_name', config('app.name', 'Saloon')) }}. All rights reserved. Premium Multi-Vendor Marketplace.
                    </p>
                    <ul class="footer-links-inline">
                        <li><a href="#privacy">Privacy</a></li>
                        <li><a href="#terms">Terms</a></li>
                        <li><a href="#cookies">Cookies</a></li>
                        <li><a href="#sitemap">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Navigation & Offcanvas Styles -->
    <style>
        /* Desktop Header - Show all elements normally */
        @media (min-width: 769px) {
            .header-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .header-logo {
                flex: 0 0 auto;
            }
            
            .header-nav {
                display: flex !important;
                flex: 1;
                justify-content: center;
            }
            
            .header-actions {
                flex: 0 0 auto;
            }
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
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
            border-radius: 30px;
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            color: #6b7280;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        .bottom-nav-item i {
            font-size: 22px;
        }

        .bottom-nav-item.active {
            color: #872341;
        }

        .bottom-nav-item:hover {
            color: #872341;
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

        .offcanvas-title {
            display: flex;
            align-items: center;
            gap: 12px;
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
        
        .offcanvas-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .offcanvas-user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: linear-gradient(135deg, #872341, #BE3144);
            border-radius: 16px;
            margin-bottom: 24px;
            color: white;
        }

        .user-avatar {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .user-role {
            font-size: 13px;
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .offcanvas-section {
            margin-bottom: 24px;
        }

        .offcanvas-section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-left: 4px;
        }

        .offcanvas-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            color: #374151;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin-bottom: 6px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .offcanvas-link:hover {
            background: linear-gradient(135deg, rgba(135, 35, 65, 0.05), rgba(190, 49, 68, 0.05));
            color: #872341;
            transform: translateX(4px);
        }

        .offcanvas-link i {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .offcanvas-logout {
            color: #dc2626;
        }

        .offcanvas-logout:hover {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(239, 68, 68, 0.05));
            color: #dc2626;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Hide desktop menu toggle on mobile */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: none;
            }
            
            /* Mobile Header - Only Logo */
            .header-nav {
                display: none !important;
            }
            
            .header-actions {
                display: none !important;
            }
            
            .header-container {
                justify-content: center;
            }
            
            .header-logo {
                margin: 0 auto;
            }
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Sticky Header on Scroll
        const header = document.getElementById('mainHeader');
        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });

        // Bootstrap Offcanvas - Auto close on link click
        const offcanvasElement = document.getElementById('mobileOffcanvas');
        if (offcanvasElement) {
            const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
            
            // Close offcanvas when clicking any navigation link
            offcanvasElement.querySelectorAll('.offcanvas-link').forEach(link => {
                link.addEventListener('click', () => {
                    bsOffcanvas.hide();
                });
            });
        }

        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mainNav = document.getElementById('mainNav');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenuToggle.classList.toggle('active');
                mainNav.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.header-container')) {
                    mobileMenuToggle.classList.remove('active');
                    mainNav.classList.remove('active');
                }
            });

            // Close menu when clicking nav link
            const navLinks = mainNav.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenuToggle.classList.remove('active');
                    mainNav.classList.remove('active');
                });
            });
        }

        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Newsletter Form Handler
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(newsletterForm);
                
                // Add your newsletter subscription logic here
                console.log('Newsletter subscription:', formData.get('email'));
                
                // Show success message (you can customize this)
                const button = newsletterForm.querySelector('.btn-newsletter');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Subscribed!';
                button.style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = '';
                    newsletterForm.reset();
                }, 3000);
            });
        }

        // Add animation on scroll for elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.animate-fadeInUp, .animate-fadeIn, .animate-scaleIn').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s ease-out';
            observer.observe(el);
        });
    </script>
    
    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <!-- Alpine.js for reactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
