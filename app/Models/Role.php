<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // Role constants
    const ADMIN = 'admin';
    const PROVIDER = 'provider';
    const CUSTOMER = 'customer';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isProvider(): bool
    {
        return $this->name === self::PROVIDER;
    }

    public function isCustomer(): bool
    {
        return $this->name === self::CUSTOMER;
    }
}
