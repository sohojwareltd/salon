<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Salon Management', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_name', 'value' => 'Salon Company Ltd', 'type' => 'string', 'group' => 'general'],
            ['key' => 'header_logo', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'footer_logo', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'opening_days', 'value' => 'Monday - Saturday', 'type' => 'string', 'group' => 'general'],
            ['key' => 'opening_hours', 'value' => '9:00 AM - 8:00 PM', 'type' => 'string', 'group' => 'general'],
            ['key' => 'phone', 'value' => '+1234567890', 'type' => 'string', 'group' => 'general'],
            ['key' => 'email', 'value' => 'info@salon.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'address', 'value' => '123 Main Street, City, Country', 'type' => 'string', 'group' => 'general'],
            
            // SEO Settings
            ['key' => 'meta_title', 'value' => 'Salon Management System', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Professional salon booking and management system', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'salon, booking, appointment, beauty', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'google_analytics', 'value' => '', 'type' => 'string', 'group' => 'seo'],
            
            // Social Media Links
            ['key' => 'facebook_url', 'value' => '', 'type' => 'string', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'string', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'string', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'string', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'string', 'group' => 'social'],
            
            // Payment Gateway Settings
            ['key' => 'stripe_key', 'value' => env('STRIPE_KEY', ''), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'stripe_secret', 'value' => env('STRIPE_SECRET', ''), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'paypal_mode', 'value' => env('PAYPAL_MODE', 'sandbox'), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'paypal_sandbox_client_id', 'value' => env('PAYPAL_SANDBOX_CLIENT_ID', ''), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'paypal_sandbox_client_secret', 'value' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'paypal_live_client_id', 'value' => env('PAYPAL_LIVE_CLIENT_ID', ''), 'type' => 'string', 'group' => 'payment'],
            ['key' => 'paypal_live_client_secret', 'value' => env('PAYPAL_LIVE_CLIENT_SECRET', ''), 'type' => 'string', 'group' => 'payment'],
            
            // Hero Section
            ['key' => 'hero_image', 'value' => '', 'type' => 'string', 'group' => 'home'],
            ['key' => 'hero_title', 'value' => 'The Fyna Barber\'s House', 'type' => 'string', 'group' => 'home'],
            ['key' => 'hero_subtitle', 'value' => 'Experience authentic style where tradition meets modern grooming excellence', 'type' => 'string', 'group' => 'home'],
            ['key' => 'hero_button_text', 'value' => 'Browse Salons', 'type' => 'string', 'group' => 'home'],
            ['key' => 'hero_button_link', 'value' => '/providers', 'type' => 'string', 'group' => 'home'],
            
            // Why Choose Us Section
            ['key' => 'feature_1_icon', 'value' => 'bi-calendar-check', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_1_title', 'value' => 'Easy Booking', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_1_description', 'value' => 'Schedule your appointment online in seconds. Choose your preferred time and professional.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'feature_2_icon', 'value' => 'bi-award', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_2_title', 'value' => 'Top Professionals', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_2_description', 'value' => 'Verified experts with years of experience and stellar customer reviews.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'feature_3_icon', 'value' => 'bi-shield-check', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_3_title', 'value' => 'Secure Payments', 'type' => 'string', 'group' => 'home'],
            ['key' => 'feature_3_description', 'value' => 'Safe and flexible payment options after your service is complete.', 'type' => 'text', 'group' => 'home'],
            
            // About Salon Section
            ['key' => 'about_salon_title', 'value' => 'Welcome to Our Salon', 'type' => 'string', 'group' => 'home'],
            ['key' => 'about_salon_description', 'value' => 'We are dedicated to providing exceptional beauty and wellness services in a comfortable and luxurious environment. Our team of experienced professionals is committed to helping you look and feel your best.', 'type' => 'text', 'group' => 'home'],
            ['key' => 'about_salon_image', 'value' => '', 'type' => 'string', 'group' => 'home'],
            
            // CTA Section
            ['key' => 'cta_title', 'value' => 'Get 20% Off Every Sunday', 'type' => 'string', 'group' => 'home'],
            ['key' => 'cta_description', 'value' => 'Join thousands of satisfied customers who trust us for their grooming needs. Book now and save!', 'type' => 'text', 'group' => 'home'],
            ['key' => 'cta_button_text', 'value' => 'Book Appointment', 'type' => 'string', 'group' => 'home'],
            ['key' => 'cta_button_link', 'value' => '/providers', 'type' => 'string', 'group' => 'home'],
            ['key' => 'cta_discount_text', 'value' => '20% OFF', 'type' => 'string', 'group' => 'home'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
