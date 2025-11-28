<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-scissors me-2"></i> Saloon
                </h5>
                <p class="mb-3" style="opacity: 0.9;">
                    Your trusted platform for discovering and booking the best salon services in Bangladesh. 
                    Connect with top-rated professionals and enjoy exceptional beauty experiences.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="footer-link">
                            <i class="bi bi-chevron-right me-1"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('providers.index') }}" class="footer-link">
                            <i class="bi bi-chevron-right me-1"></i> Find Salons
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('about') }}" class="footer-link">
                            <i class="bi bi-chevron-right me-1"></i> About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="footer-link">
                            <i class="bi bi-chevron-right me-1"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Services -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Popular Services</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('providers.index') }}?service=haircut" class="footer-link">
                            <i class="bi bi-scissors me-1"></i> Haircut
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('providers.index') }}?service=facial" class="footer-link">
                            <i class="bi bi-emoji-smile me-1"></i> Facial
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('providers.index') }}?service=manicure" class="footer-link">
                            <i class="bi bi-hand-index me-1"></i> Manicure
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('providers.index') }}?service=spa" class="footer-link">
                            <i class="bi bi-droplet me-1"></i> Spa Treatment
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Contact Us</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Dhaka, Bangladesh
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel:+8801XXXXXXXXX" class="footer-link">+880 1XXX-XXXXXX</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:info@saloon.com" class="footer-link">info@saloon.com</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock-fill me-2"></i>
                        Mon - Sat: 9AM - 9PM
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <hr class="my-4" style="opacity: 0.2;">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0" style="opacity: 0.8;">
                    &copy; {{ date('Y') }} Saloon. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="footer-link me-3">Privacy Policy</a>
                <a href="#" class="footer-link me-3">Terms of Service</a>
                <a href="#" class="footer-link">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    position: relative;
    overflow: hidden;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.5;
}

.footer > .container {
    position: relative;
    z-index: 1;
}

.footer h5, .footer h6 {
    color: var(--white);
    position: relative;
    padding-bottom: 0.5rem;
}

.footer h5::after, .footer h6::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background-color: var(--white);
    opacity: 0.5;
}

.footer-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.footer-link:hover {
    color: var(--white);
    transform: translateX(5px);
}

.btn-outline-light:hover {
    transform: scale(1.1);
    background-color: var(--white);
    color: var(--primary) !important;
}

@media (max-width: 768px) {
    .footer {
        text-align: center;
    }
    
    .footer h5::after, .footer h6::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .footer-link:hover {
        transform: translateX(0);
    }
}
</style>
