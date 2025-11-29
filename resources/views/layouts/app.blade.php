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

    <!-- Page Content -->
    <main style="padding-top: 80px; min-height: calc(100vh - 80px);">
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
                            @if(App\Facades\Settings::get('footer_logo'))
                                <img src="{{ asset('storage/' . App\Facades\Settings::get('footer_logo')) }}" alt="{{ App\Facades\Settings::get('site_name') }}" style="height: 110px; margin-bottom: 1rem;">
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
    
    <!-- Alpine.js for reactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
