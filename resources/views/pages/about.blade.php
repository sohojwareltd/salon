@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About Us</h1>
            <p class="text-xl text-gray-600">Your trusted partner in beauty and wellness</p>
        </div>

        <div class="prose prose-lg mx-auto">
            <h2>Our Mission</h2>
            <p>
                We're dedicated to connecting people with top-rated beauty professionals in their area. 
                Our platform makes it easy to discover, book, and manage appointments with skilled providers 
                who are passionate about their craft.
            </p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li><strong>Verified Professionals:</strong> All our providers are carefully vetted and reviewed by real customers.</li>
                <li><strong>Easy Booking:</strong> Book appointments online 24/7 with just a few clicks.</li>
                <li><strong>Secure Payments:</strong> Pay after your service with secure Stripe integration and optional tips.</li>
                <li><strong>Transparent Reviews:</strong> Read honest reviews and ratings from real customers.</li>
                <li><strong>Flexible Scheduling:</strong> Real-time availability and instant booking confirmation.</li>
            </ul>

            <h2>Our Story</h2>
            <p>
                Founded with a vision to revolutionize the beauty industry, we've built a platform that 
                puts convenience and quality first. We understand that finding the right salon or provider 
                can be challenging, which is why we've made it our mission to simplify the process.
            </p>

            <p>
                Today, we're proud to work with hundreds of salons and thousands of beauty professionals 
                who share our commitment to exceptional service and customer satisfaction.
            </p>
        </div>
    </div>
</div>
@endsection
