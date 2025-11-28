<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and control',
            ],
            [
                'name' => 'provider',
                'display_name' => 'Service Provider',
                'description' => 'Manage appointments and earnings',
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Book appointments and leave reviews',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('Created 3 roles');
    }
}
