<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'duration',
        'price',
        'category',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category for the service
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The providers that offer this service.
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_service');
    }

    /**
     * Get the appointments for this service.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
