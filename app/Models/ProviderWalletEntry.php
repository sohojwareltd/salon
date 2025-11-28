<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderWalletEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'appointment_id',
        'payment_id',
        'service_amount',
        'salon_amount',
        'provider_amount',
        'tips_amount',
        'total_provider_amount',
        'type',
        'notes',
    ];

    protected $casts = [
        'service_amount' => 'decimal:2',
        'salon_amount' => 'decimal:2',
        'provider_amount' => 'decimal:2',
        'tips_amount' => 'decimal:2',
        'total_provider_amount' => 'decimal:2',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
