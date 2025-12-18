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
