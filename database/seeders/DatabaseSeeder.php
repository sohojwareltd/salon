<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Seeding Single-Vendor Salon System...');

        // Step 1: Create Roles
        $this->call(RoleSeeder::class);
        
        // Step 2: Create Admin User
        $adminRole = Role::where('name', 'admin')->first();
        
        $admin = User::firstOrCreate(
            ['email' => 'admin@saloon.com'],
            [
                'name' => 'System Administrator',
                'phone' => '+8801700000000',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Created admin user (admin@saloon.com / password)');

        // Step 3: Run all other seeders
        $this->call([
            GeneralSettingsSeeder::class,
            FaqSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            RealisticDataSeeder::class,
            DownloadImagesSeeder::class,
        ]);
        
        $this->command->newLine();
        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->newLine();
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin: admin@saloon.com / password');
        $this->command->info('   Providers: james.mitchell@salon.com / password');
        $this->command->info('   Customers: john.smith@example.com / password');
    }
}
