<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'provider_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'completed_at',
        'payment_status',
        'paid_at',
        'review_requested',
        'review_submitted',
        'notes',
        'total_amount',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime',
        'review_requested' => 'boolean',
        'review_submitted' => 'boolean',
    ];

    /**
     * Get the user that owns the appointment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer (alias for user relationship)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the provider for the appointment
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
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
     * Get the service for the appointment (primary service)
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get all services for the appointment (many-to-many)
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_service');
    }

    /**
     * Get the payment for the appointment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get all payments for the appointment
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the review for the appointment
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the wallet entry for the appointment
     */
    public function walletEntry()
    {
        return $this->hasOne(ProviderWalletEntry::class);
    }

    /**
     * Check if appointment can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status === 'completed' && $this->payment_status !== 'paid';
    }

    /**
     * Check if appointment can be reviewed
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && $this->payment_status === 'paid' && !$this->review_submitted;
    }
}
