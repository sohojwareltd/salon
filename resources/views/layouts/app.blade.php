<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Saloon') }} - @yield('title', 'Welcome')</title>
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
                <div class="logo-icon">
                    <i class="bi bi-scissors"></i>
                </div>
                <span>{{ config('app.name', 'Saloon') }}</span>
            </a>

            <!-- Navigation -->
            <nav class="header-nav" id="mainNav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    Services
                </a>
                <a href="{{ route('providers.index') }}" class="nav-link {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                    Providers
                </a>
        
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    Contact
                </a>
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
                            <h3 class="footer-logo">{{ config('app.name', 'Saloon') }}</h3>
                            <p class="footer-description">
                                Your premier multi-vendor marketplace connecting customers with the finest salons, barbers, and beauty professionals. Experience luxury grooming at your fingertips.
                            </p>
                        </div>
                        <div class="footer-social">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('providers.index') }}">Browse Providers</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="#services">Our Services</a></li>
                            <li><a href="#products">Products</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- For Vendors -->
                    <div class="footer-section">
                        <h4>For Vendors</h4>
                        <ul class="footer-links">
                            <li><a href="#become-barber">Become a Barber</a></li>
                            <li><a href="#vendor-benefits">Vendor Benefits</a></li>
                            <li><a href="#pricing">Pricing Plans</a></li>
                            <li><a href="#resources">Resources</a></li>
                            <li><a href="#support">Vendor Support</a></li>
                        </ul>
                    </div>

                    <!-- Legal & Support -->
                    <div class="footer-section">
                        <h4>Support</h4>
                        <ul class="footer-links">
                            <li><a href="#help-center">Help Center</a></li>
                            <li><a href="#privacy">Privacy Policy</a></li>
                            <li><a href="#terms">Terms of Service</a></li>
                            <li><a href="#refund">Refund Policy</a></li>
                            <li><a href="#faq">FAQs</a></li>
                            <li><a href="#careers">Careers</a></li>
                        </ul>
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
                        &copy; {{ date('Y') }} {{ config('app.name', 'Saloon') }}. All rights reserved. Premium Multi-Vendor Marketplace.
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
