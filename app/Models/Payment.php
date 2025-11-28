<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'amount',
        'service_amount',
        'tip_amount',
        'total_amount',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'transaction_id',
        'status',
        'payment_method',
        'currency',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'service_amount' => 'decimal:2',
        'tip_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the appointment for the payment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the user that made the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet entry for the payment
     */
    public function walletEntry()
    {
        return $this->hasOne(ProviderWalletEntry::class);
    }
}
