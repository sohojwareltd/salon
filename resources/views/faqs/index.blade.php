@extends('layouts.app')

@section('title', 'Frequently Asked Questions - ' . config('app.name'))

@section('content')
<style>
    .faq-hero {
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .faq-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }

    .faq-hero-content {
        position: relative;
        z-index: 1;
        color: white;
        text-align: center;
    }

    .faq-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .faq-hero p {
        font-size: 1.25rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
    }

    .faq-content-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .faq-item {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: 0 4px 16px rgba(135, 35, 65, 0.15);
        transform: translateY(-2px);
    }

    .faq-question {
        width: 100%;
        padding: 24px 28px;
        background: white;
        border: none;
        text-align: left;
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        position: relative;
    }

    .faq-question:hover {
        color: #872341;
        background: #fef5f7;
    }

    .faq-question.active {
        color: #872341;
        background: #fef5f7;
        border-bottom: 2px solid #f0e6e9;
    }

    .faq-question-text {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .faq-icon {
        font-size: 1.3rem;
        color: #872341;
        flex-shrink: 0;
    }

    .faq-toggle {
        font-size: 1.5rem;
        color: #872341;
        transition: transform 0.3s ease;
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(135, 35, 65, 0.1);
    }

    .faq-question.active .faq-toggle {
        transform: rotate(180deg);
        background: #872341;
        color: white;
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.4s ease;
        padding: 0 28px;
    }

    .faq-answer.active {
        max-height: 500px;
        padding: 0 28px 24px 28px;
    }

    .faq-answer-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #4a5568;
        padding-left: 40px;
    }

    .contact-cta {
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        border-radius: 16px;
        padding: 50px;
        text-align: center;
        color: white;
        margin-top: 60px;
        box-shadow: 0 10px 30px rgba(135, 35, 65, 0.3);
    }

    .contact-cta h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .contact-cta p {
        font-size: 1.15rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    .contact-cta .btn {
        padding: 14px 36px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: white;
        color: #872341;
        border: none;
    }

    .contact-cta .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        background: #f8f9fa;
    }

    .no-faqs {
        text-align: center;
        padding: 60px 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .no-faqs i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1.5rem;
    }

    .no-faqs h3 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .no-faqs p {
        color: #718096;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .faq-hero h1 {
            font-size: 2rem;
        }

        .faq-hero p {
            font-size: 1.1rem;
        }

        .faq-question {
            padding: 18px 20px;
            font-size: 1rem;
        }

        .faq-answer-content {
            padding-left: 20px;
            font-size: 0.95rem;
        }

        .contact-cta {
            padding: 40px 30px;
        }

        .contact-cta h3 {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Hero Section -->
<div class="faq-hero">
    <div class="container">
        <div class="faq-hero-content">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about our salon services and booking process</p>
        </div>
    </div>
</div>

<!-- FAQ Content Section -->
<div class="faq-content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($faqs->isEmpty())
                    <div class="no-faqs">
                        <i class="fas fa-question-circle"></i>
                        <h3>No FAQs Available</h3>
                        <p>We're working on adding frequently asked questions. Please check back later.</p>
                    </div>
                @else
                    <!-- FAQ Items -->
                    <div class="faq-list">
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item">
                                <button class="faq-question {{ $index === 0 ? 'active' : '' }}" 
                                        onclick="toggleFaq(this)" 
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                    <span class="faq-question-text">
                                        <i class="fas fa-question-circle faq-icon"></i>
                                        {{ $faq->question }}
                                    </span>
                                    <span class="faq-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </button>
                                <div class="faq-answer {{ $index === 0 ? 'active' : '' }}">
                                    <div class="faq-answer-content">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Contact CTA -->
                <div class="contact-cta">
                    <h3>Still Have Questions?</h3>
                    <p>Can't find the answer you're looking for? Our support team is here to help.</p>
                    <a href="{{ route('contact') }}" class="btn">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFaq(button) {
    const answer = button.nextElementSibling;
    const isActive = button.classList.contains('active');
    
    // Close all other FAQs
    document.querySelectorAll('.faq-question').forEach(q => {
        q.classList.remove('active');
        q.setAttribute('aria-expanded', 'false');
    });
    
    document.querySelectorAll('.faq-answer').forEach(a => {
        a.classList.remove('active');
    });
    
    // Toggle current FAQ
    if (!isActive) {
        button.classList.add('active');
        button.setAttribute('aria-expanded', 'true');
        answer.classList.add('active');
    }
}
</script>
@endsection
