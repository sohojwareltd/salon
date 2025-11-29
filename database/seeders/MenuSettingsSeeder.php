<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MenuSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Favicon
            [
                'key' => 'favicon',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
            ],
            
            // Main Menu
            [
                'key' => 'main_menu',
                'value' => json_encode([
                    ['label' => 'Home', 'url' => '/', 'route' => 'home', 'order' => 1],
                    ['label' => 'Services', 'url' => '/services', 'route' => 'services.index', 'order' => 2],
                    ['label' => 'Providers', 'url' => '/providers', 'route' => 'providers.index', 'order' => 3],
                    ['label' => 'Contact', 'url' => '/contact', 'route' => 'contact', 'order' => 4],
                ]),
                'type' => 'json',
                'group' => 'menus',
            ],
            
            // Footer Menu 1
            [
                'key' => 'footer_menu_1_title',
                'value' => 'Quick Links',
                'type' => 'string',
                'group' => 'menus',
            ],
            [
                'key' => 'footer_menu_1',
                'value' => json_encode([
                    ['label' => 'Home', 'url' => '/', 'route' => 'home', 'order' => 1],
                    ['label' => 'Browse Providers', 'url' => '/providers', 'route' => 'providers.index', 'order' => 2],
                    ['label' => 'About Us', 'url' => '/about', 'route' => 'about', 'order' => 3],
                    ['label' => 'Contact Us', 'url' => '/contact', 'route' => 'contact', 'order' => 4],
                ]),
                'type' => 'json',
                'group' => 'menus',
            ],
            
            // Footer Menu 2
            [
                'key' => 'footer_menu_2_title',
                'value' => 'For Vendors',
                'type' => 'string',
                'group' => 'menus',
            ],
            [
                'key' => 'footer_menu_2',
                'value' => json_encode([
                    ['label' => 'Become a Barber', 'url' => '/become-provider', 'route' => '', 'order' => 1],
                    ['label' => 'Vendor Benefits', 'url' => '/vendor-benefits', 'route' => '', 'order' => 2],
                    ['label' => 'Pricing Plans', 'url' => '/pricing', 'route' => '', 'order' => 3],
                    ['label' => 'Vendor Support', 'url' => '/vendor-support', 'route' => '', 'order' => 4],
                ]),
                'type' => 'json',
                'group' => 'menus',
            ],
            
            // Footer Menu 3
            [
                'key' => 'footer_menu_3_title',
                'value' => 'Support',
                'type' => 'string',
                'group' => 'menus',
            ],
            [
                'key' => 'footer_menu_3',
                'value' => json_encode([
                    ['label' => 'Help Center', 'url' => '/help', 'route' => '', 'order' => 1],
                    ['label' => 'Privacy Policy', 'url' => '/privacy', 'route' => '', 'order' => 2],
                    ['label' => 'Terms of Service', 'url' => '/terms', 'route' => '', 'order' => 3],
                    ['label' => 'FAQs', 'url' => '/faq', 'route' => '', 'order' => 4],
                ]),
                'type' => 'json',
                'group' => 'menus',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
