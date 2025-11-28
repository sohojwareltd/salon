<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['role'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Provider relationship (if user is a provider)
     */
    public function provider()
    {
        return $this->hasOne(Provider::class,'user_id');
    }

    /**
     * User's notifications
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class)->latest();
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get the role name safely
     */
    public function getRoleName(): ?string
    {
        if (!$this->role_id) {
            return null;
        }
        
        $role = $this->role;
        
        if (!$role instanceof Role) {
            $role = Role::find($this->role_id);
        }
        
        return $role?->name;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->getRoleName() === Role::ADMIN;
    }

    /**
     * Check if user is provider
     */
    public function isProvider(): bool
    {
        return $this->getRoleName() === Role::PROVIDER;
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->getRoleName() === Role::CUSTOMER;
    }

    /**
     * Get the appointments for the user
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the reviews written by the user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the payments made by the user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
