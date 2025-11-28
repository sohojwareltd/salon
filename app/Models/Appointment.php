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
