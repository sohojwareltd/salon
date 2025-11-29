<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How do I book an appointment?',
                'answer' => 'You can book an appointment by browsing our providers, selecting a service, and choosing an available time slot. You\'ll need to create an account or log in to complete your booking.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Can I cancel or reschedule my appointment?',
                'answer' => 'Yes, you can cancel or reschedule your appointment from your dashboard. Please note that cancellations must be made at least 24 hours in advance to avoid a cancellation fee.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit cards (Visa, MasterCard, American Express), debit cards, and PayPal. Payment is processed securely through our payment gateway after you receive the service.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'How do I become a service provider on your platform?',
                'answer' => 'To become a provider, click on "Register" and select "Provider Account". Fill out the application form with your business details, certifications, and services you offer. Our team will review your application within 2-3 business days.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Are the providers licensed and certified?',
                'answer' => 'Yes, all our providers are verified professionals with proper licenses and certifications. We conduct thorough background checks and verify credentials before approving any provider on our platform.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'What is your refund policy?',
                'answer' => 'If you\'re not satisfied with a service, you can request a refund within 7 days. We will review your case and process the refund according to our terms and conditions. Partial or full refunds may be issued based on the circumstances.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer mobile services?',
                'answer' => 'Yes, many of our providers offer mobile services and can come to your location. You can filter providers by "Mobile Service" when searching to find professionals who offer this convenience.',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'How do I leave a review for a provider?',
                'answer' => 'After completing your appointment, you\'ll receive an email with a link to leave a review. You can also leave a review from your dashboard by going to your booking history and clicking on the completed appointment.',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'What if I have an issue with my appointment?',
                'answer' => 'If you experience any issues with your appointment, please contact our customer support team immediately. You can reach us through the contact form, email, or phone. We\'re here to help resolve any concerns.',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Can I book multiple services at once?',
                'answer' => 'Currently, you need to book each service separately. However, if you\'re booking with the same provider, you can schedule multiple appointments back-to-back to save time.',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }
    }
}
