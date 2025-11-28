<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use App\Models\ProviderWalletEntry;
use App\Models\Review;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Seeding Single-Vendor Salon System...');

        // Step 1: Create Roles
        $this->call(RoleSeeder::class);
        $adminRole = Role::where('name', 'admin')->first();
        $providerRole = Role::where('name', 'provider')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Step 2: Create Stripe Settings
        Setting::create(['key' => 'stripe_secret_key', 'value' => 'sk_test_YOUR_KEY', 'type' => 'string', 'group' => 'payment']);
        Setting::create(['key' => 'stripe_publishable_key', 'value' => 'pk_test_YOUR_KEY', 'type' => 'string', 'group' => 'payment']);
        Setting::create(['key' => 'stripe_webhook_secret', 'value' => 'whsec_YOUR_SECRET', 'type' => 'string', 'group' => 'payment']);
        $this->command->info('✅ Created Stripe settings');

        // Step 3: Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@saloon.com',
            'phone' => '+8801700000000',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
        $this->command->info('✅ Created admin user (admin@saloon.com / password)');

        // Step 4: Create Customer Users (50 customers)
        $customers = collect();
        for ($i = 0; $i < 50; $i++) {
            $customers->push(User::factory()->create(['role_id' => $customerRole->id]));
        }
        $this->command->info('✅ Created 50 customer users');

        // Step 5: Create Services
        $serviceData = [
            ['name' => 'Haircut', 'category' => 'Hair', 'duration' => 30, 'price' => 35.00],
            ['name' => 'Hair Coloring', 'category' => 'Hair', 'duration' => 90, 'price' => 85.00],
            ['name' => 'Balayage', 'category' => 'Hair', 'duration' => 120, 'price' => 150.00],
            ['name' => 'Hair Styling', 'category' => 'Hair', 'duration' => 45, 'price' => 45.00],
            ['name' => 'Keratin Treatment', 'category' => 'Hair', 'duration' => 150, 'price' => 200.00],
            ['name' => 'Manicure', 'category' => 'Nails', 'duration' => 45, 'price' => 30.00],
            ['name' => 'Pedicure', 'category' => 'Nails', 'duration' => 60, 'price' => 45.00],
            ['name' => 'Gel Nails', 'category' => 'Nails', 'duration' => 75, 'price' => 55.00],
            ['name' => 'Facial', 'category' => 'Spa', 'duration' => 60, 'price' => 75.00],
            ['name' => 'Swedish Massage', 'category' => 'Spa', 'duration' => 60, 'price' => 80.00],
            ['name' => 'Deep Tissue Massage', 'category' => 'Spa', 'duration' => 90, 'price' => 110.00],
            ['name' => 'Makeup Application', 'category' => 'Makeup', 'duration' => 60, 'price' => 65.00],
            ['name' => 'Bridal Makeup', 'category' => 'Makeup', 'duration' => 120, 'price' => 150.00],
            ['name' => 'Beard Trim', 'category' => 'Barber', 'duration' => 20, 'price' => 20.00],
            ['name' => 'Hot Towel Shave', 'category' => 'Barber', 'duration' => 30, 'price' => 35.00],
        ];

        $services = collect($serviceData)->map(function ($data) {
            return Service::create([
                'name' => $data['name'],
                'description' => 'Professional ' . strtolower($data['name']) . ' service.',
                'duration' => $data['duration'],
                'price' => $data['price'],
                'category' => $data['category'],
                'is_active' => true,
            ]);
        });
        $this->command->info('✅ Created ' . $services->count() . ' services');

        // Step 6: Create Providers with Users and Schedules
        $allProviders = collect();
        $providerCount = 20; // Create 20 independent providers
        
        $providerNames = [
            'Alex Johnson', 'Maria Garcia', 'James Smith', 'Sarah Williams', 'David Brown',
            'Emily Davis', 'Michael Wilson', 'Jessica Martinez', 'Daniel Anderson', 'Laura Taylor',
            'Christopher Thomas', 'Amanda Jackson', 'Matthew White', 'Jennifer Harris', 'Joshua Clark',
            'Ashley Lewis', 'Andrew Robinson', 'Stephanie Walker', 'Ryan Young', 'Melissa Hall'
        ];
        
        for ($p = 0; $p < $providerCount; $p++) {
            // Create provider user
            $providerUser = User::factory()->create(['role_id' => $providerRole->id]);
            
            $name = $providerNames[$p];
            $expertise = $services->random(rand(3, 5))->pluck('name')->join(', ');
            
            // Create provider with ALL columns
            $provider = Provider::create([
                'user_id' => $providerUser->id,
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@provider.com',
                'phone' => '+880170' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'photo' => null,
                'expertise' => $expertise,
                'bio' => 'Experienced professional specializing in ' . strtolower($expertise) . '. Passionate about delivering exceptional service.',
                'facebook' => rand(0, 1) ? 'https://facebook.com/' . strtolower(str_replace(' ', '', $name)) : null,
                'instagram' => rand(0, 1) ? 'https://instagram.com/' . strtolower(str_replace(' ', '', $name)) : null,
                'twitter' => null,
                'youtube' => null,
                'linkedin' => null,
                'website' => null,
                'average_rating' => 0.00,
                'total_reviews' => 0,
                'is_active' => true,
                'break_start' => '13:00:00',
                'break_end' => '14:00:00',
                'buffer_time' => rand(5, 15),
                'commission_percentage' => rand(70, 85),
                'wallet_balance' => 0.00,
            ]);
            
            // Link user to provider
            $providerUser->update([
                'provider_id' => $provider->id,
            ]);
            
            // Attach random services to provider
            $provider->services()->attach(
                $services->random(rand(3, 7))->pluck('id')
            );
            
            // Create weekly schedule (Sunday to Saturday, Friday off)
            for ($day = 0; $day <= 6; $day++) {
                ProviderSchedule::create([
                    'provider_id' => $provider->id,
                    'weekday' => $day,
                    'start_time' => $day === 5 ? null : '09:00:00', // Friday off
                    'end_time' => $day === 5 ? null : '20:00:00',
                    'is_off' => $day === 5, // Friday is off
                ]);
            }
            
            $allProviders->push($provider);
        }
        $this->command->info('✅ Created ' . $allProviders->count() . ' providers with schedules');

        // Step 8: Create 200 Appointments (mixture of statuses)
        $appointments = collect();
        $statusDistribution = [
            'completed' => 120, // 60% completed
            'confirmed' => 40,  // 20% confirmed
            'pending' => 30,    // 15% pending
            'cancelled' => 10,  // 5% cancelled
        ];
        
        foreach ($statusDistribution as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $customer = $customers->random();
                $provider = $allProviders->random();
                $service = $provider->services->random();
                
                // Create dates based on status
                $date = match($status) {
                    'completed' => now()->subDays(rand(1, 60)),
                    'confirmed' => now()->addDays(rand(1, 30)),
                    'pending' => now()->addDays(rand(1, 14)),
                    'cancelled' => now()->subDays(rand(1, 30)),
                };
                
                $appointment = Appointment::create([
                    'user_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_id' => $service->id,
                    'appointment_date' => $date->format('Y-m-d'),
                    'start_time' => ['09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00', '16:00:00'][rand(0, 5)],
                    'end_time' => ['09:30:00', '10:30:00', '11:30:00', '14:30:00', '15:30:00', '16:30:00'][rand(0, 5)],
                    'status' => $status,
                    'completed_at' => $status === 'completed' ? $date : null,
                    'payment_status' => $status === 'completed' ? (rand(1, 100) > 20 ? 'paid' : 'pending') : 'pending',
                    'paid_at' => null,
                    'review_requested' => false,
                    'review_submitted' => false,
                ]);
                
                $appointments->push($appointment);
            }
        }
        $this->command->info('✅ Created 200 appointments (120 completed, 40 confirmed, 30 pending, 10 cancelled)');

        // Step 9: Create Payments and Wallet Entries for Completed & Paid Appointments
        $completedPaidAppointments = $appointments->filter(function ($appointment) {
            return $appointment->status === 'completed' && $appointment->payment_status === 'paid';
        });
        
        $paymentCount = 0;
        $walletEntryCount = 0;
        
        foreach ($completedPaidAppointments as $appointment) {
            $tipAmount = rand(0, 100) > 60 ? rand(500, 2000) / 100 : 0; // 40% chance of tip
            $serviceAmount = $appointment->service->price;
            $totalAmount = $serviceAmount + $tipAmount;
            
            // Create payment
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'service_amount' => $serviceAmount,
                'tip_amount' => $tipAmount,
                'total_amount' => $totalAmount,
                'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
                'stripe_charge_id' => 'ch_test_' . uniqid(),
                'transaction_id' => 'txn_' . uniqid(),
                'status' => 'completed',
                'payment_method' => 'stripe',
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'provider_id' => $appointment->provider_id,
                ],
                'paid_at' => $appointment->completed_at,
            ]);
            
            // Update appointment
            $appointment->update(['paid_at' => $payment->paid_at, 'review_requested' => true]);
            
            $paymentCount++;
            
            // Create wallet entry (provider keeps 80% of service amount + all tips)
            $provider = $appointment->provider;
            
            $providerCommissionRate = $provider->commission_percentage / 100;
            $providerAmount = $serviceAmount * $providerCommissionRate;
            $platformAmount = $serviceAmount - $providerAmount; // Platform takes the rest
            $totalProviderAmount = $providerAmount + $tipAmount;
            
            ProviderWalletEntry::create([
                'provider_id' => $provider->id,
                'appointment_id' => $appointment->id,
                'payment_id' => $payment->id,
                'service_amount' => $serviceAmount,
                'salon_amount' => $platformAmount, // Using salon_amount for platform fee
                'provider_amount' => $providerAmount,
                'tips_amount' => $tipAmount,
                'total_provider_amount' => $totalProviderAmount,
                'type' => 'earning',
                'notes' => "Payment for {$appointment->service->name}",
            ]);
            
            // Update provider wallet balance
            $provider->increment('wallet_balance', $totalProviderAmount);
            
            $walletEntryCount++;
        }
        
        $this->command->info('✅ Created ' . $paymentCount . ' payments and ' . $walletEntryCount . ' wallet entries');

        // Step 10: Create Reviews for Some Paid Appointments
        $reviewableAppointments = $appointments->filter(function ($appointment) {
            return $appointment->status === 'completed' && 
                   $appointment->payment_status === 'paid' &&
                   rand(1, 100) > 30; // 70% review rate
        });
        
        foreach ($reviewableAppointments as $appointment) {
            Review::factory()->create([
                'user_id' => $appointment->user_id,
                'provider_id' => $appointment->provider_id,
                'appointment_id' => $appointment->id,
            ]);
            
            $appointment->update(['review_submitted' => true]);
        }
        
        $this->command->info('✅ Created ' . $reviewableAppointments->count() . ' reviews');

        // Step 11: Update Provider Ratings
        foreach ($allProviders as $provider) {
            $reviews = Review::where('provider_id', $provider->id)->get();
            if ($reviews->count() > 0) {
                $provider->average_rating = $reviews->avg('rating');
                $provider->total_reviews = $reviews->count();
                $provider->save();
            }
        }
        $this->command->info('✅ Updated provider ratings');

        // Final Summary
        $this->command->newLine();
        $this->command->info('🎉 Database Seeding Completed Successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   • 3 Roles (admin, provider, customer)');
        $this->command->info('   • 1 Admin User (admin@saloon.com / password)');
        $this->command->info('   • ' . $allProviders->count() . ' Providers');
        $this->command->info('   • 50 Customers');
        $this->command->info('   • 15 Services');
        $this->command->info('   • 200 Appointments');
        $this->command->info('   • ' . $paymentCount . ' Payments');
        $this->command->info('   • ' . $walletEntryCount . ' Wallet Entries');
        $this->command->info('   • ' . $reviewableAppointments->count() . ' Reviews');
        $this->command->newLine();
        $this->command->info('🔐 Test Credentials:');
        $this->command->info('   Admin: admin@saloon.com / password');
        $this->command->info('   All other users: password');
    }
}
