<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Role;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ProviderSchedule;
use App\Models\ProviderWalletEntry;
use Carbon\Carbon;

class RealisticEuropeanSeeder extends Seeder
{
    private $providerNames = [
        ['name' => 'Alessandro Rossi', 'expertise' => 'Master Barber & Beard Specialist', 'country' => 'Italy'],
        ['name' => 'Hans Schmidt', 'expertise' => 'Classic German Cuts & Styling', 'country' => 'Germany'],
        ['name' => 'Pierre Dubois', 'expertise' => 'Parisian Hair Artist', 'country' => 'France'],
        ['name' => 'João Silva', 'expertise' => 'Contemporary Portuguese Styling', 'country' => 'Portugal'],
        ['name' => 'Antonio García', 'expertise' => 'Spanish Hair Designer', 'country' => 'Spain'],
        ['name' => 'Marco Bianchi', 'expertise' => 'Italian Style Expert', 'country' => 'Italy'],
        ['name' => 'Lars Andersson', 'expertise' => 'Scandinavian Hair Specialist', 'country' => 'Sweden'],
        ['name' => 'Klaus Müller', 'expertise' => 'Traditional & Modern Cuts', 'country' => 'Germany'],
        ['name' => 'François Martin', 'expertise' => 'French Hair Couture', 'country' => 'France'],
        ['name' => 'Miguel Santos', 'expertise' => 'Lisbon Hair Studio', 'country' => 'Portugal'],
        ['name' => 'Carlos López', 'expertise' => 'Barcelona Styling Expert', 'country' => 'Spain'],
        ['name' => 'Luca Ferrari', 'expertise' => 'Milan Fashion Cuts', 'country' => 'Italy'],
        ['name' => 'Erik Johansson', 'expertise' => 'Nordic Hair Design', 'country' => 'Sweden'],
        ['name' => 'Wolfgang Fischer', 'expertise' => 'Berlin Hair Artist', 'country' => 'Germany'],
        ['name' => 'Olivier Bernard', 'expertise' => 'Lyon Hair Specialist', 'country' => 'France'],
    ];

    private $customerNames = [
        'James Anderson', 'Michael Brown', 'Robert Wilson', 'William Davis', 'David Miller',
        'Richard Moore', 'Joseph Taylor', 'Thomas Jackson', 'Christopher White', 'Daniel Harris',
        'Matthew Martin', 'Anthony Thompson', 'Mark Garcia', 'Donald Martinez', 'Steven Robinson',
        'Paul Clark', 'Andrew Rodriguez', 'Joshua Lewis', 'Kenneth Lee', 'Kevin Walker',
        'Brian Hall', 'George Allen', 'Edward Young', 'Ronald Hernandez', 'Timothy King',
        'Jason Wright', 'Jeffrey Lopez', 'Ryan Hill', 'Jacob Scott', 'Gary Green',
        'Nicholas Adams', 'Eric Baker', 'Jonathan Gonzalez', 'Stephen Nelson', 'Larry Carter',
        'Justin Mitchell', 'Scott Perez', 'Brandon Roberts', 'Frank Turner', 'Benjamin Phillips',
        'Gregory Campbell', 'Raymond Parker', 'Samuel Evans', 'Patrick Edwards', 'Alexander Collins',
        'Jack Stewart', 'Dennis Sanchez', 'Jerry Morris', 'Tyler Rogers', 'Aaron Reed',
    ];

    private $services = [
        ['name' => 'Classic Haircut', 'duration' => 45, 'price' => 35],
        ['name' => 'Premium Haircut & Styling', 'duration' => 60, 'price' => 55],
        ['name' => 'Beard Trim & Shape', 'duration' => 30, 'price' => 25],
        ['name' => 'Full Beard Service', 'duration' => 45, 'price' => 40],
        ['name' => 'Hair & Beard Combo', 'duration' => 75, 'price' => 70],
        ['name' => 'Hot Towel Shave', 'duration' => 40, 'price' => 45],
        ['name' => 'Hair Coloring', 'duration' => 90, 'price' => 85],
        ['name' => 'Hair Treatment', 'duration' => 50, 'price' => 60],
        ['name' => 'Kids Haircut', 'duration' => 30, 'price' => 25],
        ['name' => 'Senior Haircut', 'duration' => 35, 'price' => 30],
        ['name' => 'Hair Wash & Blow Dry', 'duration' => 25, 'price' => 20],
        ['name' => 'Scalp Treatment', 'duration' => 45, 'price' => 50],
        ['name' => 'Texture & Perm', 'duration' => 120, 'price' => 95],
        ['name' => 'Hair Extension Consultation', 'duration' => 30, 'price' => 30],
        ['name' => 'Wedding/Event Styling', 'duration' => 90, 'price' => 120],
    ];

    public function run()
    {
        $this->command->info('🌍 Starting European-style realistic seeding...');

        // Create Roles if not exist
        if (Role::count() == 0) {
            $this->call(RoleSeeder::class);
        }

        // Clear existing data
        $this->command->info('Clearing existing data...');
        ProviderWalletEntry::query()->delete();
        Payment::query()->delete();
        Review::query()->delete();
        Appointment::query()->delete();
        ProviderSchedule::query()->delete();
        Service::query()->delete();
        Provider::query()->delete();
        User::whereHas('role', function($q) {
            $q->whereIn('name', ['provider', 'customer']);
        })->delete();

        // Create storage directory for avatars
        Storage::disk('public')->makeDirectory('avatars');

        // Create Admin
        $this->command->info('Creating admin user...');
        $adminRole = Role::where('name', Role::ADMIN)->first();
        User::firstOrCreate(
            ['email' => 'admin@salon.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create Providers
        $this->command->info('Creating European providers with realistic data...');
        $providerRole = Role::where('name', Role::PROVIDER)->first();
        $providers = [];

        foreach ($this->providerNames as $index => $providerData) {
            // Generate avatar
            $avatarPath = $this->generateAvatar($providerData['name'], $index);
            
            $user = User::create([
                'name' => $providerData['name'],
                'email' => 'provider' . ($index + 1) . '@salon.com',
                'password' => Hash::make('password'),
                'role_id' => $providerRole->id,
            ]);

            $provider = Provider::create([
                'user_id' => $user->id,
                'name' => $providerData['name'],
                'email' => $user->email,
                'phone' => $this->generateEuropeanPhone($providerData['country']),
                'photo' => $avatarPath,
                'expertise' => $providerData['expertise'],
                'bio' => $this->generateBio($providerData['name'], $providerData['country']),
                'facebook' => 'https://facebook.com/' . strtolower(str_replace(' ', '', $providerData['name'])),
                'instagram' => '@' . strtolower(str_replace(' ', '_', $providerData['name'])),
                'twitter' => '@' . strtolower(str_replace(' ', '', substr($providerData['name'], 0, 12))),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $providerData['name'])) . '.com',
                'break_start' => '13:00:00',
                'break_end' => '14:00:00',
                'buffer_time' => rand(10, 20),
                'commission_percentage' => rand(15, 30),
                'wallet_balance' => rand(500, 5000),
            ]);

            // Create weekly schedule (Monday to Saturday, Sunday off)
            foreach (range(0, 6) as $weekday) {
                ProviderSchedule::create([
                    'provider_id' => $provider->id,
                    'weekday' => $weekday,
                    'start_time' => $weekday == 0 ? '09:00:00' : '08:00:00',
                    'end_time' => $weekday == 6 ? '16:00:00' : '19:00:00',
                    'is_off' => $weekday == 0 ? true : false,
                ]);
            }

            // Create services for provider (8-12 services each)
            $selectedServices = collect($this->services)->random(rand(8, 12));
            $providerServices = collect();
            
            foreach ($selectedServices as $serviceData) {
                // Check if service exists, or create with slight price variation
                $service = Service::firstOrCreate(
                    ['name' => $serviceData['name']],
                    [
                        'description' => 'Professional ' . strtolower($serviceData['name']) . ' service with years of expertise.',
                        'duration' => $serviceData['duration'],
                        'price' => $serviceData['price'] + rand(-5, 10),
                        'category' => 'Hair Care',
                        'is_active' => true,
                    ]
                );
                
                // Attach service to provider
                if (!$provider->services()->where('service_id', $service->id)->exists()) {
                    $provider->services()->attach($service->id);
                }
                
                $providerServices->push($service);
            }

            $providers[] = [
                'provider' => $provider,
                'services' => $providerServices,
            ];

            $this->command->info("✓ Created provider: {$providerData['name']} ({$providerData['country']})");
        }

        // Create Customers
        $this->command->info('Creating customers...');
        $customerRole = Role::where('name', Role::CUSTOMER)->first();
        $customers = [];

        foreach ($this->customerNames as $index => $name) {
            $avatarPath = $this->generateAvatar($name, $index + 100);
            
            $user = User::create([
                'name' => $name,
                'email' => 'customer' . ($index + 1) . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $customerRole->id,
            ]);

            $customers[] = $user;
        }

        // Create Appointments with realistic distribution
        $this->command->info('Creating appointments with realistic patterns...');
        $appointmentCount = 0;
        $paymentCount = 0;
        $reviewCount = 0;

        foreach ($providers as $providerData) {
            $provider = $providerData['provider'];
            $services = $providerData['services'];

            // Create 12-20 appointments per provider
            $numAppointments = rand(12, 20);

            for ($i = 0; $i < $numAppointments; $i++) {
                $customer = $customers[array_rand($customers)];
                
                // Random date in the past 90 days or next 30 days
                $isPast = rand(0, 100) < 70; // 70% past appointments
                if ($isPast) {
                    $daysAgo = rand(1, 90);
                    $date = Carbon::now()->subDays($daysAgo);
                } else {
                    $daysAhead = rand(1, 30);
                    $date = Carbon::now()->addDays($daysAhead);
                }

                // Skip if Sunday (provider's day off)
                if ($date->dayOfWeek == 0) {
                    continue;
                }

                // Random time slot during working hours (8:00-19:00, excluding lunch 13:00-14:00)
                $hour = rand(8, 17);
                if ($hour == 13) $hour = 14;
                $minute = [0, 30][rand(0, 1)];
                $startTime = sprintf('%02d:%02d:00', $hour, $minute);

                // Select 1-2 services
                $selectedServices = $services->random(rand(1, 2));
                $totalDuration = $selectedServices->sum('duration');
                $totalAmount = $selectedServices->sum('price');

                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime)
                    ->addMinutes($totalDuration)
                    ->format('H:i:s');

                // Determine status based on date
                if ($isPast) {
                    $statuses = ['completed', 'completed', 'completed', 'cancelled', 'no_show'];
                    $status = $statuses[rand(0, 4)];
                } else {
                    $statuses = ['pending', 'confirmed', 'confirmed'];
                    $status = $statuses[rand(0, 2)];
                }

                $appointment = Appointment::create([
                    'user_id' => $customer->id,
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'service_id' => $selectedServices->first()->id,
                    'appointment_date' => $date->format('Y-m-d H:i:s'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $status,
                    'payment_status' => $status == 'completed' ? 'paid' : 'pending',
                    'total_amount' => $totalAmount,
                    'completed_at' => $status == 'completed' ? $date : null,
                    'paid_at' => $status == 'completed' ? $date->addMinutes($totalDuration) : null,
                    'notes' => rand(0, 100) < 30 ? $this->generateAppointmentNotes() : null,
                ]);

                // Attach services
                $appointment->services()->attach($selectedServices->pluck('id'));

                $appointmentCount++;

                // Create payment if completed
                if ($status == 'completed') {
                    $commissionAmount = $totalAmount * ($provider->commission_percentage / 100);
                    $providerEarning = $totalAmount - $commissionAmount;

                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'user_id' => $customer->id,
                        'provider_id' => $provider->id,
                        'amount' => $totalAmount,
                        'commission_amount' => $commissionAmount,
                        'provider_earning' => $providerEarning,
                        'payment_method' => ['card', 'cash', 'online'][rand(0, 2)],
                        'transaction_id' => 'TXN' . strtoupper(uniqid()),
                        'status' => 'completed',
                        'paid_at' => $date->addMinutes($totalDuration),
                    ]);

                    // Create wallet entry
                    ProviderWalletEntry::create([
                        'provider_id' => $provider->id,
                        'appointment_id' => $appointment->id,
                        'type' => 'credit',
                        'amount' => $providerEarning,
                        'description' => "Payment for appointment #{$appointment->id}",
                        'balance_after' => $provider->wallet_balance,
                    ]);

                    $paymentCount++;

                    // Create review (60% chance)
                    if (rand(0, 100) < 60) {
                        Review::create([
                            'appointment_id' => $appointment->id,
                            'provider_id' => $provider->id,
                            'user_id' => $customer->id,
                            'rating' => rand(4, 5),
                            'comment' => $this->generateReview(),
                            'created_at' => $date->addHours(rand(2, 48)),
                        ]);
                        $reviewCount++;
                    }
                }
            }
        }

        $this->command->info("\n✅ Seeding completed successfully!");
        $this->command->info("📊 Summary:");
        $this->command->info("   - Providers: " . count($providers));
        $this->command->info("   - Customers: " . count($customers));
        $this->command->info("   - Services: " . Service::count());
        $this->command->info("   - Appointments: {$appointmentCount}");
        $this->command->info("   - Payments: {$paymentCount}");
        $this->command->info("   - Reviews: {$reviewCount}");
        $this->command->info("\n🔐 Login Credentials:");
        $this->command->info("   Admin: admin@salon.com / password");
        $this->command->info("   Providers: provider1@salon.com to provider15@salon.com / password");
        $this->command->info("   Customers: customer1@example.com to customer50@example.com / password");
    }

    private function generateAvatar($name, $seed)
    {
        // Use DiceBear API for realistic avatars
        $initials = collect(explode(' ', $name))->map(fn($n) => $n[0])->join('');
        $colors = ['872341', 'BE3144', '2563eb', '059669', 'dc2626', '7c3aed', 'ea580c'];
        $color = $colors[$seed % count($colors)];
        
        // Generate avatar URL
        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($name) . 
                     "&size=200&background={$color}&color=fff&bold=true&format=png";
        
        // Download and save
        $avatarContent = @file_get_contents($avatarUrl);
        if ($avatarContent) {
            $filename = 'avatars/' . strtolower(str_replace(' ', '_', $name)) . '_' . $seed . '.png';
            Storage::disk('public')->put($filename, $avatarContent);
            return $filename;
        }
        
        return null;
    }

    private function generateEuropeanPhone($country)
    {
        $formats = [
            'Italy' => '+39 ' . rand(300, 399) . ' ' . rand(1000000, 9999999),
            'Germany' => '+49 ' . rand(150, 179) . ' ' . rand(10000000, 99999999),
            'France' => '+33 ' . rand(6, 7) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
            'Portugal' => '+351 ' . rand(910, 969) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
            'Spain' => '+34 ' . rand(600, 799) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
            'Sweden' => '+46 ' . rand(70, 79) . ' ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
        ];

        return $formats[$country] ?? '+44 ' . rand(7000, 7999) . ' ' . rand(100000, 999999);
    }

    private function generateBio($name, $country)
    {
        $bios = [
            "Passionate about creating stunning hairstyles with over 15 years of experience. Specialized in classic and contemporary cuts.",
            "Award-winning stylist dedicated to bringing out the best in every client. Expert in European hair trends and techniques.",
            "Master barber with a keen eye for detail. Creating confidence through exceptional grooming services.",
            "Professional hairstylist trained in {$country}. Combining traditional techniques with modern trends.",
            "Expert in precision cutting and styling. Committed to delivering personalized service for every client.",
        ];

        return $bios[array_rand($bios)];
    }

    private function generateAppointmentNotes()
    {
        $notes = [
            'Please use low-volume products',
            'Prefer short on sides, longer on top',
            'Sensitive scalp - gentle products please',
            'Need consultation for hair color',
            'Regular customer - knows usual style',
            'First time visit',
            'Special occasion haircut',
        ];

        return $notes[array_rand($notes)];
    }

    private function generateReview()
    {
        $reviews = [
            'Excellent service! Very professional and skilled. Will definitely come back.',
            'Best haircut I have had in years. Highly recommend!',
            'Great attention to detail. Fantastic experience overall.',
            'Very pleased with the result. Professional and friendly service.',
            'Outstanding work! Exactly what I wanted.',
            'Superb haircut and styling. Worth every penny.',
            'Incredible skill and professionalism. Very satisfied!',
            'Amazing service from start to finish. Highly recommended.',
            'Perfect haircut! Will be returning regularly.',
            'Excellent barber with great expertise. Very happy with the results.',
        ];

        return $reviews[array_rand($reviews)];
    }
}
