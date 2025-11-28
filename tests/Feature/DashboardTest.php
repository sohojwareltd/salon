<?php

use App\Models\User;
use App\Models\Salon;
use App\Models\Provider;
use App\Models\Role;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('salon owner can access salon dashboard', function () {
    $salonRole = Role::where('name', 'salon')->first();
    $user = User::factory()->create(['role_id' => $salonRole->id]);
    $salon = Salon::factory()->create(['owner_id' => $user->id]);
    $user->update(['salon_id' => $salon->id]);
    
    $this->actingAs($user)
        ->get(route('salon.dashboard'))
        ->assertStatus(200)
        ->assertSee('Dashboard');
});

test('provider can access provider dashboard', function () {
    $providerRole = Role::where('name', 'provider')->first();
    $user = User::factory()->create(['role_id' => $providerRole->id]);
    $salon = Salon::factory()->create();
    $provider = Provider::factory()->create(['salon_id' => $salon->id, 'user_id' => $user->id]);
    $user->update(['provider_id' => $provider->id]);
    
    $this->actingAs($user)
        ->get(route('provider.dashboard'))
        ->assertStatus(200)
        ->assertSee('Dashboard');
});

test('customer can access customer dashboard', function () {
    $customerRole = Role::where('name', 'customer')->first();
    $customer = User::factory()->create(['role_id' => $customerRole->id]);
    
    $this->actingAs($customer)
        ->get(route('customer.dashboard'))
        ->assertStatus(200)
        ->assertSee('Dashboard');
});

test('salon owner cannot access provider dashboard', function () {
    $salonRole = Role::where('name', 'salon')->first();
    $user = User::factory()->create(['role_id' => $salonRole->id]);
    $salon = Salon::factory()->create(['owner_id' => $user->id]);
    $user->update(['salon_id' => $salon->id]);
    
    $this->actingAs($user)
        ->get(route('provider.dashboard'))
        ->assertStatus(403);
});

test('customer cannot access salon dashboard', function () {
    $customerRole = Role::where('name', 'customer')->first();
    $customer = User::factory()->create(['role_id' => $customerRole->id]);
    
    $this->actingAs($customer)
        ->get(route('salon.dashboard'))
        ->assertStatus(403);
});
