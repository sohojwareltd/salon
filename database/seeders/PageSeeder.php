<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'description' => 'Learn more about our salon and our commitment to excellence',
                'content' => '<h2>Welcome to Our Salon</h2>
                <p>We are dedicated to providing exceptional beauty and wellness services in a comfortable and luxurious environment. Our team of experienced professionals is committed to helping you look and feel your best.</p>
                
                <h3>Our Mission</h3>
                <p>To deliver high-quality salon services that exceed our clients\' expectations while maintaining the highest standards of professionalism and customer care.</p>
                
                <h3>Our Values</h3>
                <ul>
                    <li>Excellence in service delivery</li>
                    <li>Customer satisfaction</li>
                    <li>Continuous learning and improvement</li>
                    <li>Clean and safe environment</li>
                </ul>',
                'meta_title' => 'About Us - Professional Salon Services',
                'meta_description' => 'Learn about our salon, our mission, and our commitment to providing exceptional beauty services.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'description' => 'How we collect, use, and protect your personal information',
                'content' => '<h2>Privacy Policy</h2>
                <p>Last updated: ' . date('F d, Y') . '</p>
                
                <h3>Information We Collect</h3>
                <p>We collect information that you provide directly to us, including your name, email address, phone number, and appointment preferences.</p>
                
                <h3>How We Use Your Information</h3>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Process and manage your appointments</li>
                    <li>Send you appointment reminders and confirmations</li>
                    <li>Improve our services</li>
                    <li>Communicate with you about our services</li>
                </ul>
                
                <h3>Data Security</h3>
                <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'description' => 'Terms and conditions for using our services',
                'content' => '<h2>Terms of Service</h2>
                <p>By accessing and using our services, you agree to be bound by these Terms of Service.</p>
                
                <h3>Appointments</h3>
                <p>Appointments must be cancelled at least 24 hours in advance. Late cancellations or no-shows may be subject to a cancellation fee.</p>
                
                <h3>Payment</h3>
                <p>Payment is due at the time of service. We accept cash and major credit cards.</p>
                
                <h3>Refunds</h3>
                <p>Refunds are available within 7 days of service if you are not satisfied. Please contact us to discuss any concerns.</p>',
                'meta_title' => 'Terms of Service',
                'meta_description' => 'Read our terms of service to understand the rules and guidelines for using our salon services.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }
}
