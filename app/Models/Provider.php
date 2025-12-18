<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'photo',
        'expertise',
        'bio',
        'average_rating',
        'total_reviews',
        'is_active',
        'break_start',
        'break_end',
        'buffer_time',
        'commission_percentage',
        'wallet_balance',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'website',
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'is_active' => 'boolean',
        'commission_percentage' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
    ];

    /**
     * Get the user associated with the provider
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Override getAttribute to handle salon as a computed attribute
     */
    public function getAttribute($key)
    {
        if ($key === 'salon') {
            return $this->getSalonData();
        }
        
        return parent::getAttribute($key);
    }

    /**
     * Get the single salon data from settings
     */
    public function getSalonData()
    {
        $admin = User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->first();

        // Create an object with salon properties from admin and settings
        $salonData = (object)[
            'id' => 1,
            'name' => Setting::get('salon_name', config('app.name')),
            'phone' => Setting::get('salon_phone', $admin?->phone ?? ''),
            'email' => Setting::get('salon_email', $admin?->email ?? ''),
            'address' => Setting::get('salon_address', ''),
            'city' => Setting::get('salon_city', ''),
            'state' => Setting::get('salon_state', ''),
            'country' => Setting::get('salon_country', ''),
            'postal_code' => Setting::get('salon_postal_code', ''),
            'website' => Setting::get('salon_website', ''),
            'subdomain_url' => Setting::get('salon_subdomain_url', ''),
            'logo' => Setting::get('salon_logo', ''),
            'cover_image' => Setting::get('salon_cover_image', ''),
            'description' => Setting::get('salon_description', ''),
        ];

        // Add hasSubdomain method as callable
        $salonData->hasSubdomain = function() use ($salonData) {
            return !empty($salonData->subdomain_url);
        };

        return $salonData;
    }

    /**
     * The services that the provider offers
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_service');
    }

    /**
     * Get the appointments for the provider
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reviews for the provider
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the schedules for the provider
     */
    public function schedules()
    {
        return $this->hasMany(ProviderSchedule::class);
    }

    /**
     * Get the exceptions for the provider
     */
    public function exceptions()
    {
        return $this->hasMany(ProviderException::class);
    }

    /**
     * Get the wallet entries for the provider
     */
    public function walletEntries()
    {
        return $this->hasMany(ProviderWalletEntry::class);
    }
}
