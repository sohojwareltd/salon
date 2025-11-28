# Complete Salon Booking System - Single Vendor (Full Implementation Guide)

## Project Overview
Create a single vendor salon booking website with Laravel + Filament for salon owner dashboard and custom Blade frontend for customers. Exact same design and color scheme as the multi-vendor system.

---

## Phase 1: Project Setup & Database Design

### Step 1.1: Initialize Laravel Project
```bash
# Create new Laravel 11 project
composer create-project laravel/laravel salon-booking

cd salon-booking

# Install required packages
composer require filament/filament:"^3.0" --with-all-dependencies
composer require laravel/breeze --dev
composer require stripe/stripe-php
composer require laravel/sanctum

# Install Breeze
php artisan breeze:install blade

# Run migrations
php artisan migrate

# Install Filament
php artisan filament:install --panels

# Configure environment
# Copy .env.example to .env and configure:
# - Database: salon_booking
# - Stripe keys
# - Mail settings
```

### Step 1.2: Database Schema
```bash
# Create migrations
php artisan make:migration create_salon_settings_table
php artisan make:migration create_services_table
php artisan make:migration create_providers_table
php artisan make:migration create_provider_services_table
php artisan make:migration create_appointments_table
php artisan make:migration create_appointment_services_table
php artisan make:migration create_payments_table
php artisan make:migration create_wallet_entries_table
php artisan make:migration create_reviews_table
php artisan make:migration create_notifications_table
php artisan make:migration create_time_slots_table
php artisan make:migration add_role_to_users_table
```

#### 1. users table (extends default)
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable();
    $table->string('avatar')->nullable();
    $table->enum('role', ['customer', 'admin'])->default('customer');
});
```

#### 2. salon_settings table
```php
Schema::create('salon_settings', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('logo')->nullable();
    $table->string('cover_image')->nullable();
    $table->string('phone');
    $table->string('email');
    $table->text('address');
    $table->string('city');
    $table->string('state');
    $table->string('zip');
    $table->text('description')->nullable();
    $table->time('opening_time')->default('09:00:00');
    $table->time('closing_time')->default('21:00:00');
    $table->string('timezone')->default('Asia/Dhaka');
    $table->string('stripe_key')->nullable();
    $table->string('stripe_secret')->nullable();
    $table->decimal('commission_percentage', 5, 2)->default(20.00);
    $table->timestamps();
});
```

#### 3. services table
```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('salon_settings_id')->default(1)->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->text('description')->nullable();
    $table->integer('duration'); // in minutes
    $table->decimal('price', 10, 2);
    $table->string('image')->nullable();
    $table->string('category')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

#### 4. providers table
```php
Schema::create('providers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('salon_settings_id')->default(1)->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->text('bio')->nullable();
    $table->string('specialization')->nullable();
    $table->string('photo')->nullable();
    $table->decimal('hourly_rate', 10, 2)->nullable();
    $table->decimal('commission_percentage', 5, 2)->default(20.00);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

#### 5. provider_services table (pivot)
```php
Schema::create('provider_services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->decimal('custom_price', 10, 2)->nullable();
    $table->integer('custom_duration')->nullable(); // in minutes
    $table->timestamps();
});
```

#### 6. appointments table
```php
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // customer
    $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
    $table->date('appointment_date');
    $table->time('start_time');
    $table->time('end_time');
    $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
    $table->decimal('total_amount', 10, 2);
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->index(['appointment_date', 'provider_id']);
    $table->index('status');
});
```

#### 7. appointment_services table (pivot)
```php
Schema::create('appointment_services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->decimal('price', 10, 2);
    $table->integer('duration'); // in minutes
    $table->timestamps();
});
```

#### 8. payments table
```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 10, 2);
    $table->decimal('tip_amount', 10, 2)->default(0);
    $table->string('stripe_payment_id')->nullable();
    $table->string('payment_method')->default('card');
    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
```

#### 9. wallet_entries table
```php
Schema::create('wallet_entries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
    $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('amount', 10, 2);
    $table->enum('type', ['earning', 'withdrawal'])->default('earning');
    $table->enum('status', ['pending', 'processed'])->default('pending');
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
    
    $table->index(['provider_id', 'status']);
});
```

#### 10. reviews table
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
    $table->foreignId('salon_settings_id')->default(1)->constrained()->cascadeOnDelete();
    $table->integer('rating'); // 1-5
    $table->text('comment')->nullable();
    $table->boolean('is_visible')->default(true);
    $table->timestamps();
    
    $table->index(['provider_id', 'rating']);
});
```

#### 11. notifications table
```php
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('message');
    $table->string('url')->nullable();
    $table->enum('type', ['booking', 'approval', 'payment', 'complete', 'review_request'])->default('booking');
    $table->boolean('is_read')->default(false);
    $table->timestamps();
    
    $table->index(['user_id', 'is_read']);
    $table->index('created_at');
});
```

#### 12. time_slots table
```php
Schema::create('time_slots', function (Blueprint $table) {
    $table->id();
    $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
    $table->integer('day_of_week'); // 0-6 (Sunday-Saturday)
    $table->time('start_time');
    $table->time('end_time');
    $table->boolean('is_available')->default(true);
    $table->timestamps();
    
    $table->index(['provider_id', 'day_of_week']);
});
```

### Step 1.3: Models & Relationships

#### User Model
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function provider(): HasOne
    {
        return $this->hasOne(Provider::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
```

#### Provider Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $fillable = [
        'user_id', 'salon_settings_id', 'name', 'bio', 'specialization',
        'photo', 'hourly_rate', 'commission_percentage', 'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salonSettings(): BelongsTo
    {
        return $this->belongsTo(SalonSettings::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_services')
            ->withPivot('custom_price', 'custom_duration')
            ->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function walletEntries(): HasMany
    {
        return $this->hasMany(WalletEntry::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function totalEarnings(): float
    {
        return $this->walletEntries()
            ->where('type', 'earning')
            ->where('status', 'processed')
            ->sum('amount');
    }
}
```

#### Service Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'salon_settings_id', 'name', 'description', 'duration',
        'price', 'image', 'category', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    public function salonSettings(): BelongsTo
    {
        return $this->belongsTo(SalonSettings::class);
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'provider_services')
            ->withPivot('custom_price', 'custom_duration')
            ->withTimestamps();
    }

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services')
            ->withPivot('price', 'duration')
            ->withTimestamps();
    }
}
```

#### Appointment Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'user_id', 'provider_id', 'appointment_date', 'start_time',
        'end_time', 'status', 'total_amount', 'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'appointment_services')
            ->withPivot('price', 'duration')
            ->withTimestamps();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where(function($q) {
            $q->where('appointment_date', '<', now()->toDateString())
              ->orWhere('status', 'completed');
        })->orderBy('appointment_date', 'desc');
    }
}
```

#### Notification Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'url', 'type', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['icon', 'color'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'booking' => 'bi-calendar-check',
            'approval' => 'bi-check-circle',
            'payment' => 'bi-credit-card',
            'complete' => 'bi-check-circle-fill',
            'review_request' => 'bi-star',
            default => 'bi-bell',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->type) {
            'booking' => '#3b82f6',
            'approval' => '#10b981',
            'payment' => '#f59e0b',
            'complete' => '#059669',
            'review_request' => '#f59e0b',
            default => '#6b7280',
        };
    }
}
```

---

## Phase 2: Filament Admin Panel (Salon Owner Dashboard)

### Step 2.1: Setup Filament

```bash
# Create admin user
php artisan make:filament-user

# Create Filament resources
php artisan make:filament-resource Appointment --generate
php artisan make:filament-resource Service --generate
php artisan make:filament-resource Provider --generate
php artisan make:filament-resource Customer --model=User --generate
php artisan make:filament-resource Review --generate
php artisan make:filament-resource WalletEntry --generate
php artisan make:filament-resource SalonSettings --generate

# Create widgets
php artisan make:filament-widget StatsOverview
php artisan make:filament-widget AppointmentsChart
php artisan make:filament-widget RecentAppointments
php artisan make:filament-widget TopProviders
```

### Step 2.2: Dashboard Widgets

#### StatsOverviewWidget
```php
<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $thisMonth = now()->startOfMonth();
        
        return [
            Stat::make('Total Bookings', Appointment::where('created_at', '>=', $thisMonth)->count())
                ->description('This month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
                
            Stat::make('Total Revenue', '৳' . number_format(
                Appointment::where('created_at', '>=', $thisMonth)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
                2
            ))
                ->description('This month')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Active Providers', Provider::where('is_active', true)->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
                
            Stat::make('Average Rating', number_format(Review::avg('rating'), 1) . ' / 5.0')
                ->description('Overall salon rating')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
```

### Step 2.3: Filament Resources

#### AppointmentResource
```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Bookings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable(),
                
            Forms\Components\Select::make('provider_id')
                ->relationship('provider', 'name')
                ->required()
                ->searchable(),
                
            Forms\Components\DatePicker::make('appointment_date')
                ->required()
                ->native(false),
                
            Forms\Components\TimePicker::make('start_time')
                ->required()
                ->native(false),
                
            Forms\Components\TimePicker::make('end_time')
                ->required()
                ->native(false),
                
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
                
            Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->prefix('৳')
                ->required(),
                
            Forms\Components\Textarea::make('notes')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('provider.name')->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('start_time')->time('h:i A'),
                Tables\Columns\TextColumn::make('total_amount')->money('BDT')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('provider')
                    ->relationship('provider', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
```

---

## Phase 3: Customer Frontend (Blade Templates)

### Step 3.1: Design System Setup

Create `public/assets/css/app.css`:

```css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

:root {
    /* Primary Colors */
    --primary-dark: #09122C;
    --primary-1: #872341;
    --primary-2: #BE3144;
    --primary-3: #E17564;
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, #872341, #BE3144);
    --gradient-accent: linear-gradient(135deg, #BE3144, #E17564);
    
    /* Neutrals */
    --white: #FFFFFF;
    --light-gray: #f8f9fa;
    --gray: #6c757d;
    --dark-gray: #343a40;
    --black: #000000;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
    
    /* Border Radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    
    /* Transitions */
    --transition-fast: 150ms ease;
    --transition-base: 300ms ease;
    --transition-slow: 500ms ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: var(--dark-gray);
    background: var(--light-gray);
}

/* Gradient Background */
.gradient-bg {
    background: var(--gradient-primary);
}

.gradient-text {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Buttons */
.btn-primary {
    background: var(--gradient-primary);
    color: var(--white);
    border: none;
    padding: 12px 24px;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
    display: inline-block;
    text-decoration: none;
    text-align: center;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: var(--white);
    color: var(--primary-1);
    border: 2px solid var(--primary-1);
    padding: 12px 24px;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
    display: inline-block;
    text-decoration: none;
}

.btn-secondary:hover {
    background: var(--primary-1);
    color: var(--white);
}

/* Cards */
.card {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
    overflow: hidden;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.card-body {
    padding: 24px;
}

/* Stat Card */
.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 24px;
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 4px;
}

.stat-content p {
    font-size: 14px;
    color: var(--gray);
    margin: 0;
}

/* Input Fields */
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius-sm);
    font-size: 14px;
    transition: all var(--transition-base);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-1);
    box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
}

/* Badges */
.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-pending {
    background: #FEF3C7;
    color: #D97706;
}

.badge-confirmed {
    background: #DBEAFE;
    color: #2563EB;
}

.badge-completed {
    background: #D1FAE5;
    color: #059669;
}

.badge-cancelled {
    background: #FEE2E2;
    color: #DC2626;
}

/* Utility Classes */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.text-center {
    text-align: center;
}

.mt-1 { margin-top: 8px; }
.mt-2 { margin-top: 16px; }
.mt-3 { margin-top: 24px; }
.mt-4 { margin-top: 32px; }
.mt-5 { margin-top: 48px; }

.mb-1 { margin-bottom: 8px; }
.mb-2 { margin-bottom: 16px; }
.mb-3 { margin-bottom: 24px; }
.mb-4 { margin-bottom: 32px; }
.mb-5 { margin-bottom: 48px; }

.grid {
    display: grid;
    gap: 24px;
}

.grid-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-3 {
    grid-template-columns: repeat(3, 1fr);
}

.grid-4 {
    grid-template-columns: repeat(4, 1fr);
}

@media (max-width: 768px) {
    .grid-2,
    .grid-3,
    .grid-4 {
        grid-template-columns: 1fr;
    }
}
```

### Step 3.2: Public Pages

#### Homepage (welcome.blade.php)
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $salon->name }} - Premium Salon Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                @if($salon->logo)
                    <img src="{{ asset('storage/' . $salon->logo) }}" alt="{{ $salon->name }}" height="40">
                @else
                    <span class="gradient-text fw-bold fs-4">{{ $salon->name }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('providers.index') }}">Providers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    @auth
                        <li class="nav-item ms-3">
                            <a class="btn-primary" href="{{ route('customer.dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="btn-primary" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, rgba(135, 35, 65, 0.9), rgba(190, 49, 68, 0.9)), url('{{ asset('storage/' . $salon->cover_image) }}'); background-size: cover; background-position: center; padding: 120px 0; color: white;">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3">{{ $salon->name }}</h1>
            <p class="lead mb-4">{{ $salon->description }}</p>
            <a href="{{ route('booking.select-service') }}" class="btn btn-light btn-lg px-5 py-3">
                <i class="bi bi-calendar-check me-2"></i>Book Now
            </a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-4 gradient-text">Our Services</h2>
            <div class="row g-4">
                @foreach($services as $service)
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" alt="{{ $service->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $service->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($service->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span><i class="bi bi-clock text-primary"></i> {{ $service->duration }} min</span>
                                <span class="fw-bold text-primary">৳{{ number_format($service->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('services.index') }}" class="btn-secondary">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">{{ $salon->name }}</h5>
                    <p>{{ $salon->description }}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Contact</h5>
                    <p><i class="bi bi-telephone me-2"></i>{{ $salon->phone }}</p>
                    <p><i class="bi bi-envelope me-2"></i>{{ $salon->email }}</p>
                    <p><i class="bi bi-geo-alt me-2"></i>{{ $salon->address }}</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Business Hours</h5>
                    <p>{{ date('h:i A', strtotime($salon->opening_time)) }} - {{ date('h:i A', strtotime($salon->closing_time)) }}</p>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0">© {{ date('Y') }} {{ $salon->name }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## Phase 4: Booking Flow Controllers

### BookingController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class BookingController extends Controller
{
    public function selectService()
    {
        $services = Service::where('is_active', true)->get();
        return view('booking.select-service', compact('services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
        ]);

        session(['booking_services' => $request->services]);
        return redirect()->route('booking.select-provider');
    }

    public function selectProvider()
    {
        $serviceIds = session('booking_services');
        if (!$serviceIds) {
            return redirect()->route('booking.select-service');
        }

        $providers = Provider::where('is_active', true)
            ->whereHas('services', function($q) use ($serviceIds) {
                $q->whereIn('services.id', $serviceIds);
            })
            ->with('services')
            ->get();

        return view('booking.select-provider', compact('providers'));
    }

    public function storeProvider(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
        ]);

        session(['booking_provider' => $request->provider_id]);
        return redirect()->route('booking.select-datetime');
    }

    public function selectDatetime()
    {
        $providerId = session('booking_provider');
        if (!$providerId) {
            return redirect()->route('booking.select-provider');
        }

        $provider = Provider::with('services')->findOrFail($providerId);
        return view('booking.select-datetime', compact('provider'));
    }

    public function getAvailableSlots(Request $request)
    {
        $date = $request->date;
        $providerId = $request->provider_id;

        // Get provider's schedule for the day
        $dayOfWeek = date('w', strtotime($date));
        $timeSlot = TimeSlot::where('provider_id', $providerId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$timeSlot) {
            return response()->json(['slots' => []]);
        }

        // Get existing appointments
        $existingAppointments = Appointment::where('provider_id', $providerId)
            ->where('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // Generate time slots (30 min intervals)
        $slots = [];
        $currentTime = strtotime($timeSlot->start_time);
        $endTime = strtotime($timeSlot->end_time);

        while ($currentTime < $endTime) {
            $slotTime = date('H:i:s', $currentTime);
            
            // Check if slot is available
            $isBooked = false;
            foreach ($existingAppointments as $appointment) {
                if ($slotTime >= $appointment->start_time && $slotTime < $appointment->end_time) {
                    $isBooked = true;
                    break;
                }
            }

            if (!$isBooked) {
                $slots[] = [
                    'time' => $slotTime,
                    'display' => date('h:i A', $currentTime),
                ];
            }

            $currentTime += 30 * 60; // Add 30 minutes
        }

        return response()->json(['slots' => $slots]);
    }

    public function storeDatetime(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);

        session([
            'booking_date' => $request->date,
            'booking_time' => $request->time,
        ]);

        return redirect()->route('booking.summary');
    }

    public function summary()
    {
        $serviceIds = session('booking_services');
        $providerId = session('booking_provider');
        $date = session('booking_date');
        $time = session('booking_time');

        if (!$serviceIds || !$providerId || !$date || !$time) {
            return redirect()->route('booking.select-service');
        }

        $services = Service::whereIn('id', $serviceIds)->get();
        $provider = Provider::findOrFail($providerId);
        
        $total = $services->sum('price');
        $duration = $services->sum('duration');

        return view('booking.summary', compact('services', 'provider', 'date', 'time', 'total', 'duration'));
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'tip_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $serviceIds = session('booking_services');
        $providerId = session('booking_provider');
        $date = session('booking_date');
        $time = session('booking_time');

        $services = Service::whereIn('id', $serviceIds)->get();
        $subtotal = $services->sum('price');
        $tipAmount = $request->tip_amount ?? 0;
        $total = $subtotal + $tipAmount;

        session([
            'booking_tip' => $tipAmount,
            'booking_notes' => $request->notes,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($services as $service) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bdt',
                    'product_data' => [
                        'name' => $service->name,
                    ],
                    'unit_amount' => $service->price * 100,
                ],
                'quantity' => 1,
            ];
        }

        if ($tipAmount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bdt',
                    'product_data' => [
                        'name' => 'Tip for Provider',
                    ],
                    'unit_amount' => $tipAmount * 100,
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('booking.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('booking.summary'),
        ]);

        return redirect($session->url);
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->session_id;
        
        // Retrieve session from Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = StripeSession::retrieve($sessionId);

        // Create appointment
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'provider_id' => session('booking_provider'),
            'appointment_date' => session('booking_date'),
            'start_time' => session('booking_time'),
            'end_time' => date('H:i:s', strtotime(session('booking_time')) + (session('booking_duration') * 60)),
            'status' => 'pending',
            'total_amount' => session('booking_total'),
            'notes' => session('booking_notes'),
        ]);

        // Attach services
        $services = Service::whereIn('id', session('booking_services'))->get();
        foreach ($services as $service) {
            $appointment->services()->attach($service->id, [
                'price' => $service->price,
                'duration' => $service->duration,
            ]);
        }

        // Create payment record
        Payment::create([
            'appointment_id' => $appointment->id,
            'user_id' => auth()->id(),
            'amount' => session('booking_total') - session('booking_tip'),
            'tip_amount' => session('booking_tip'),
            'stripe_payment_id' => $session->payment_intent,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Create notifications
        makeNotification(
            auth()->id(),
            'Booking Confirmed',
            'Your appointment has been booked successfully.',
            route('customer.bookings.show', $appointment->id),
            'booking'
        );

        // Clear session
        session()->forget(['booking_services', 'booking_provider', 'booking_date', 'booking_time', 'booking_tip', 'booking_notes']);

        return view('booking.success', compact('appointment'));
    }
}
```

---

## Phase 5: Helper Functions

Create `app/helpers.php`:

```php
<?php

use App\Models\Notification;

if (!function_exists('makeNotification')) {
    function makeNotification($userId, $title, $message, $url = null, $type = 'booking')
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'type' => $type,
            'is_read' => false,
        ]);
    }
}

if (!function_exists('notifyUsers')) {
    function notifyUsers(array $userIds, $title, $message, $url = null, $type = 'booking')
    {
        foreach ($userIds as $userId) {
            makeNotification($userId, $title, $message, $url, $type);
        }
    }
}
```

Register in `composer.json`:
```json
"autoload": {
    "files": [
        "app/helpers.php"
    ]
}
```

Then run: `composer dump-autoload`

---

## Phase 6: Routes

### web.php
```php
<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Booking Flow
Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/select-service', [BookingController::class, 'selectService'])->name('select-service');
    Route::post('/store-service', [BookingController::class, 'storeService'])->name('store-service');
    Route::get('/select-provider', [BookingController::class, 'selectProvider'])->name('select-provider');
    Route::post('/store-provider', [BookingController::class, 'storeProvider'])->name('store-provider');
    Route::get('/select-datetime', [BookingController::class, 'selectDatetime'])->name('select-datetime');
    Route::get('/available-slots', [BookingController::class, 'getAvailableSlots'])->name('available-slots');
    Route::post('/store-datetime', [BookingController::class, 'storeDatetime'])->name('store-datetime');
    Route::get('/summary', [BookingController::class, 'summary'])->name('summary');
    Route::post('/payment', [BookingController::class, 'createPayment'])->name('payment');
    Route::get('/success', [BookingController::class, 'paymentSuccess'])->name('success');
});

// Customer Dashboard
Route::middleware(['auth'])->prefix('customer-dashboard')->name('customer.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{appointment}', [DashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{appointment}/cancel', [DashboardController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::get('/review/{appointment}', [DashboardController::class, 'createReview'])->name('review.create');
    Route::post('/review', [DashboardController::class, 'storeReview'])->name('review.store');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
});

require __DIR__.'/auth.php';
```

---

## Phase 7: Environment Configuration

### .env
```env
APP_NAME="Salon Booking"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salon_booking
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@salon.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Phase 8: Seeders

### SalonSettingsSeeder
```php
<?php

namespace Database\Seeders;

use App\Models\SalonSettings;
use Illuminate\Database\Seeder;

class SalonSettingsSeeder extends Seeder
{
    public function run()
    {
        SalonSettings::create([
            'name' => 'Glamour Studio',
            'phone' => '+880-1234-567890',
            'email' => 'info@glamourstudio.com',
            'address' => '123 Beauty Street',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'zip' => '1200',
            'description' => 'Premium salon services for modern men and women',
            'opening_time' => '09:00:00',
            'closing_time' => '21:00:00',
            'timezone' => 'Asia/Dhaka',
            'commission_percentage' => 20.00,
        ]);
    }
}
```

### DatabaseSeeder
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SalonSettingsSeeder::class,
            AdminUserSeeder::class,
            ServicesSeeder::class,
            ProvidersSeeder::class,
        ]);
    }
}
```

---

## Deployment Checklist

1. ✅ Set APP_ENV=production
2. ✅ Set APP_DEBUG=false
3. ✅ Run `php artisan key:generate`
4. ✅ Configure database
5. ✅ Run migrations: `php artisan migrate --seed`
6. ✅ Configure Stripe keys
7. ✅ Set up mail driver
8. ✅ Run optimizations:
   ```bash
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
9. ✅ Set up queue worker
10. ✅ Configure SSL certificate

---

## Usage Instructions

### For Developers:
Work through each phase sequentially. Copy the code examples and modify as needed for your specific requirements.

### For Copilot/AI:
Ask Copilot to implement each phase:
```
"Implement Phase X: [Phase Title]. Create all necessary files as specified in the documentation."
```

### Testing:
1. Create admin user via Filament
2. Add sample services and providers
3. Test booking flow as customer
4. Verify Stripe payment integration
5. Test notification system
6. Review Filament admin panel features

---

**Project Complete! 🎉**

This documentation provides a complete roadmap for building a single-vendor salon booking system with the same design and features as the multi-vendor system.
