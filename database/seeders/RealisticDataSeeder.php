<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use App\Models\Review;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎨 Seeding realistic salon data...');

        // Clear existing data first
        $this->command->info('Clearing existing data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Review::truncate();
        Payment::truncate();
        Appointment::truncate();
        ProviderSchedule::truncate();
        DB::table('provider_service')->truncate();
        Provider::truncate();
        Service::truncate();
        User::whereHas('role', function($q) {
            $q->whereIn('name', ['provider', 'customer']);
        })->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('✅ Cleared existing data');

        // Get roles
        $providerRole = Role::where('name', 'provider')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Create Services with realistic images
        $this->command->info('Creating services...');
        $services = [
            [
                'name' => 'Classic Haircut',
                'description' => 'Professional haircut with wash, styling advice, and finishing touches. Perfect for maintaining your style.',
                'price' => 25.00,
                'duration' => 30,
                'category' => 'haircut',
                'image' => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Haircut & Styling',
                'description' => 'Deluxe haircut with consultation, wash, precision cutting, blow dry, and premium styling.',
                'price' => 45.00,
                'duration' => 60,
                'category' => 'haircut',
                'image' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Beard Trim & Shaping',
                'description' => 'Expert beard trimming, shaping, and grooming with hot towel treatment.',
                'price' => 20.00,
                'duration' => 25,
                'category' => 'beard',
                'image' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Royal Shave Experience',
                'description' => 'Traditional hot lather shave with straight razor, hot towels, and soothing aftershave.',
                'price' => 35.00,
                'duration' => 40,
                'category' => 'shaving',
                'image' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Coloring',
                'description' => 'Professional hair coloring service with premium products and expert color matching.',
                'price' => 60.00,
                'duration' => 90,
                'category' => 'haircut',
                'image' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Facial Treatment',
                'description' => 'Rejuvenating facial with deep cleansing, exfoliation, and moisturizing mask.',
                'price' => 50.00,
                'duration' => 45,
                'category' => 'facial',
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Head & Shoulder Massage',
                'description' => 'Relaxing massage focusing on head, neck, and shoulders to relieve tension and stress.',
                'price' => 30.00,
                'duration' => 30,
                'category' => 'massage',
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Kids Haircut',
                'description' => 'Gentle and patient haircut service for children under 12.',
                'price' => 18.00,
                'duration' => 20,
                'category' => 'haircut',
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Spa Treatment',
                'description' => 'Deep conditioning treatment with scalp massage, steam, and nourishing mask.',
                'price' => 40.00,
                'duration' => 50,
                'category' => 'massage',
                'image' => 'https://images.unsplash.com/photo-1519415387722-a1c3bbef716c?w=800',
                'is_active' => true,
            ],
            [
                'name' => 'Combo: Haircut + Beard Trim',
                'description' => 'Complete grooming package with haircut and beard trimming.',
                'price' => 40.00,
                'duration' => 50,
                'category' => 'haircut',
                'image' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=800',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
        $this->command->info('✅ Created ' . count($services) . ' services');

        // Create Provider Users (Mix of Men and Women)
        $this->command->info('Creating providers...');
        
        $providers = [
            // Male Providers
            [
                'name' => 'James Mitchell',
                'email' => 'james.mitchell@salon.com',
                'phone' => '+1234567801',
                'photo' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'gender' => 'male',
                'expertise' => 'Master Barber specializing in classic and modern haircuts',
                'experience' => 8,
                'rating' => 4.9,
            ],
            [
                'name' => 'David Chen',
                'email' => 'david.chen@salon.com',
                'phone' => '+1234567802',
                'photo' => 'https://randomuser.me/api/portraits/men/2.jpg',
                'gender' => 'male',
                'expertise' => 'Expert in beard grooming and traditional shaving',
                'experience' => 6,
                'rating' => 4.8,
            ],
            [
                'name' => 'Marcus Johnson',
                'email' => 'marcus.johnson@salon.com',
                'phone' => '+1234567803',
                'photo' => 'https://randomuser.me/api/portraits/men/3.jpg',
                'gender' => 'male',
                'expertise' => 'Precision haircuts and contemporary styling',
                'experience' => 5,
                'rating' => 4.7,
            ],
            [
                'name' => 'Robert Williams',
                'email' => 'robert.williams@salon.com',
                'phone' => '+1234567804',
                'photo' => 'https://randomuser.me/api/portraits/men/4.jpg',
                'gender' => 'male',
                'expertise' => 'Hair coloring specialist and creative styling',
                'experience' => 7,
                'rating' => 4.8,
            ],
            
            // Female Providers
            [
                'name' => 'Sarah Anderson',
                'email' => 'sarah.anderson@salon.com',
                'phone' => '+1234567805',
                'photo' => 'https://randomuser.me/api/portraits/women/1.jpg',
                'gender' => 'female',
                'expertise' => 'Senior stylist with expertise in hair treatments and coloring',
                'experience' => 9,
                'rating' => 4.9,
            ],
            [
                'name' => 'Emily Parker',
                'email' => 'emily.parker@salon.com',
                'phone' => '+1234567806',
                'photo' => 'https://randomuser.me/api/portraits/women/2.jpg',
                'gender' => 'female',
                'expertise' => 'Facial treatments and skin care specialist',
                'experience' => 6,
                'rating' => 4.8,
            ],
            [
                'name' => 'Jessica Martinez',
                'email' => 'jessica.martinez@salon.com',
                'phone' => '+1234567807',
                'photo' => 'https://randomuser.me/api/portraits/women/3.jpg',
                'gender' => 'female',
                'expertise' => 'Hair spa and wellness treatments expert',
                'experience' => 5,
                'rating' => 4.7,
            ],
            [
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@salon.com',
                'phone' => '+1234567808',
                'photo' => 'https://randomuser.me/api/portraits/women/4.jpg',
                'gender' => 'female',
                'expertise' => 'Massage therapy and relaxation specialist',
                'experience' => 4,
                'rating' => 4.6,
            ],
            [
                'name' => 'Amanda Rodriguez',
                'email' => 'amanda.rodriguez@salon.com',
                'phone' => '+1234567809',
                'photo' => 'https://randomuser.me/api/portraits/women/5.jpg',
                'gender' => 'female',
                'expertise' => 'Kids haircuts and family styling expert',
                'experience' => 7,
                'rating' => 4.8,
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@salon.com',
                'phone' => '+1234567810',
                'photo' => 'https://randomuser.me/api/portraits/men/5.jpg',
                'gender' => 'male',
                'expertise' => 'All-round barber with premium grooming skills',
                'experience' => 10,
                'rating' => 4.9,
            ],
        ];

        $providerModels = [];
        foreach ($providers as $providerData) {
            $user = User::create([
                'name' => $providerData['name'],
                'email' => $providerData['email'],
                'phone' => $providerData['phone'],
                'password' => bcrypt('password'),
                'role_id' => $providerRole->id,
                'email_verified_at' => now(),
            ]);

            $provider = Provider::create([
                'user_id' => $user->id,
                'name' => $providerData['name'],
                'email' => $providerData['email'],
                'phone' => $providerData['phone'],
                'photo' => $providerData['photo'],
                'expertise' => $providerData['expertise'],
                'bio' => $providerData['experience'] . ' years of experience. Passionate about delivering exceptional grooming experiences. Trained in the latest techniques and committed to client satisfaction.',
                'is_active' => true,
                'average_rating' => $providerData['rating'],
                'total_reviews' => rand(15, 50),
            ]);

            $providerModels[] = $provider;

            // Attach random services to provider
            $serviceIds = Service::inRandomOrder()->limit(rand(3, 6))->pluck('id');
            $provider->services()->attach($serviceIds);

            // Create schedule for each provider (Monday to Saturday)
            // weekday: 0=Sunday, 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday
            for ($weekday = 1; $weekday <= 6; $weekday++) {
                ProviderSchedule::create([
                    'provider_id' => $provider->id,
                    'weekday' => $weekday,
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'is_off' => false,
                ]);
            }
        }
        $this->command->info('✅ Created ' . count($providers) . ' providers with schedules');

        // Create Customer Users
        $this->command->info('Creating customers...');
        $customers = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com', 'phone' => '+1234567901'],
            ['name' => 'Emma Wilson', 'email' => 'emma.wilson@example.com', 'phone' => '+1234567902'],
            ['name' => 'Michael Davis', 'email' => 'michael.davis@example.com', 'phone' => '+1234567903'],
            ['name' => 'Sophia Taylor', 'email' => 'sophia.taylor@example.com', 'phone' => '+1234567904'],
            ['name' => 'William Moore', 'email' => 'william.moore@example.com', 'phone' => '+1234567905'],
            ['name' => 'Olivia Martin', 'email' => 'olivia.martin@example.com', 'phone' => '+1234567906'],
            ['name' => 'James Anderson', 'email' => 'james.anderson@example.com', 'phone' => '+1234567907'],
            ['name' => 'Isabella Thomas', 'email' => 'isabella.thomas@example.com', 'phone' => '+1234567908'],
            ['name' => 'Benjamin Lee', 'email' => 'benjamin.lee@example.com', 'phone' => '+1234567909'],
            ['name' => 'Mia Harris', 'email' => 'mia.harris@example.com', 'phone' => '+1234567910'],
            ['name' => 'Lucas Clark', 'email' => 'lucas.clark@example.com', 'phone' => '+1234567911'],
            ['name' => 'Charlotte Lewis', 'email' => 'charlotte.lewis@example.com', 'phone' => '+1234567912'],
            ['name' => 'Alexander Walker', 'email' => 'alexander.walker@example.com', 'phone' => '+1234567913'],
            ['name' => 'Amelia Hall', 'email' => 'amelia.hall@example.com', 'phone' => '+1234567914'],
            ['name' => 'Daniel Allen', 'email' => 'daniel.allen@example.com', 'phone' => '+1234567915'],
        ];

        $customerModels = [];
        foreach ($customers as $customerData) {
            $customer = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'password' => bcrypt('password'),
                'role_id' => $customerRole->id,
                'email_verified_at' => now(),
            ]);
            $customerModels[] = $customer;
        }
        $this->command->info('✅ Created ' . count($customers) . ' customers');

        // Create Appointments with varied statuses
        $this->command->info('Creating appointments...');
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $appointmentCount = 0;

        foreach ($customerModels as $customer) {
            $numAppointments = rand(1, 4);
            
            for ($i = 0; $i < $numAppointments; $i++) {
                $provider = $providerModels[array_rand($providerModels)];
                $service = $provider->services->random();
                $status = $statuses[array_rand($statuses)];
                
                // Create appointments within last 60 days
                $daysAgo = rand(1, 60);
                $appointmentDate = now()->subDays($daysAgo)->format('Y-m-d');
                $appointmentTime = sprintf('%02d:00:00', rand(9, 17));

                $appointment = Appointment::create([
                    'user_id' => $customer->id,
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_id' => $service->id,
                    'appointment_date' => $appointmentDate,
                    'start_time' => $appointmentTime,
                    'end_time' => date('H:i:s', strtotime($appointmentTime) + ($service->duration * 60)),
                    'status' => $status,
                    'total_amount' => $service->price,
                    'notes' => 'Looking forward to the service!',
                    'payment_status' => $status === 'completed' ? 'paid' : 'pending',
                    'completed_at' => $status === 'completed' ? now()->subDays($daysAgo) : null,
                ]);

                $appointmentCount++;

                // Create payment for completed appointments
                if ($status === 'completed') {
                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'user_id' => $customer->id,
                        'service_amount' => $service->price,
                        'tip_amount' => 0,
                        'total_amount' => $service->price,
                        'amount' => $service->price,
                        'payment_method' => ['card', 'cash'][rand(0, 1)],
                        'status' => 'completed',
                        'transaction_id' => 'TXN' . strtoupper(substr(md5(uniqid()), 0, 12)),
                        'paid_at' => now()->subDays($daysAgo),
                    ]);

                    // Create review for some completed appointments (70% chance)
                    if (rand(1, 10) <= 7) {
                        $ratings = [4, 4, 4, 5, 5, 5, 5, 3, 5, 4]; // Weighted towards higher ratings
                        $comments = [
                            'Excellent service! Very professional and skilled.',
                            'Really happy with my haircut. Will definitely come back!',
                            'Great experience. The stylist knew exactly what I wanted.',
                            'Very clean salon and friendly staff. Highly recommend!',
                            'Best haircut I\'ve had in years. Thank you!',
                            'Professional service with attention to detail.',
                            'Fantastic! Love my new look.',
                            'Good service, but had to wait a bit longer than expected.',
                            'Amazing transformation! Exceeded my expectations.',
                            'Very satisfied with the results. Worth the price!',
                        ];

                        Review::create([
                            'appointment_id' => $appointment->id,
                            'user_id' => $customer->id,
                            'provider_id' => $provider->id,
                            'rating' => $ratings[array_rand($ratings)],
                            'comment' => $comments[array_rand($comments)],
                        ]);
                    }
                }
            }
        }
        $this->command->info('✅ Created ' . $appointmentCount . ' appointments with payments and reviews');

        $this->command->info('🎉 Realistic data seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📧 Login Credentials:');
        $this->command->info('   Providers: [email] / password');
        $this->command->info('   Customers: [email] / password');
        $this->command->info('   Example: james.mitchell@salon.com / password');
    }
}
