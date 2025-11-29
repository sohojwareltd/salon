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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
