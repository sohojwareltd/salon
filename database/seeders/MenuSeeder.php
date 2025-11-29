<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Main Menu (Header)
        $mainMenu = Menu::create([
            'name' => 'main_menu',
            'title' => 'Main Navigation',
            'location' => 'header',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $mainMenuItems = [
            ['label' => 'Home', 'url' => '/', 'route' => 'home', 'icon' => 'bi-house', 'sort_order' => 1],
            ['label' => 'Services', 'url' => '/services', 'route' => 'services.index', 'icon' => 'bi-scissors', 'sort_order' => 2],
            ['label' => 'Providers', 'url' => '/providers', 'route' => 'providers.index', 'icon' => 'bi-people', 'sort_order' => 3],
            ['label' => 'Contact', 'url' => '/contact', 'route' => 'contact', 'icon' => 'bi-envelope', 'sort_order' => 4],
        ];

        foreach ($mainMenuItems as $item) {
            $mainMenu->items()->create($item);
        }

        // Footer Menu 1
        $footerMenu1 = Menu::create([
            'name' => 'footer_menu_1',
            'title' => 'Quick Links',
            'location' => 'footer_1',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $footerMenu1Items = [
            ['label' => 'Home', 'url' => '/', 'route' => 'home', 'sort_order' => 1],
            ['label' => 'Browse Providers', 'url' => '/providers', 'route' => 'providers.index', 'sort_order' => 2],
            ['label' => 'About Us', 'url' => '/about', 'route' => 'about', 'sort_order' => 3],
            ['label' => 'Contact Us', 'url' => '/contact', 'route' => 'contact', 'sort_order' => 4],
        ];

        foreach ($footerMenu1Items as $item) {
            $footerMenu1->items()->create($item);
        }

        // Footer Menu 2
        $footerMenu2 = Menu::create([
            'name' => 'footer_menu_2',
            'title' => 'For Vendors',
            'location' => 'footer_2',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $footerMenu2Items = [
            ['label' => 'Become a Provider', 'url' => '/become-provider', 'route' => '', 'sort_order' => 1],
            ['label' => 'Vendor Benefits', 'url' => '/vendor-benefits', 'route' => '', 'sort_order' => 2],
            ['label' => 'Pricing Plans', 'url' => '/pricing', 'route' => '', 'sort_order' => 3],
            ['label' => 'Vendor Support', 'url' => '/vendor-support', 'route' => '', 'sort_order' => 4],
        ];

        foreach ($footerMenu2Items as $item) {
            $footerMenu2->items()->create($item);
        }

        // Footer Menu 3
        $footerMenu3 = Menu::create([
            'name' => 'footer_menu_3',
            'title' => 'Support',
            'location' => 'footer_3',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $footerMenu3Items = [
            ['label' => 'Help Center', 'url' => '/help', 'route' => '', 'sort_order' => 1],
            ['label' => 'Privacy Policy', 'url' => '/privacy', 'route' => '', 'sort_order' => 2],
            ['label' => 'Terms of Service', 'url' => '/terms', 'route' => '', 'sort_order' => 3],
            ['label' => 'FAQs', 'url' => '/faq', 'route' => '', 'sort_order' => 4],
        ];

        foreach ($footerMenu3Items as $item) {
            $footerMenu3->items()->create($item);
        }
    }
}
