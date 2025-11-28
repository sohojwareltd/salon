<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'url',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['icon', 'color'];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute()
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

    /**
     * Get notification color based on type.
     */
    public function getColorAttribute()
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
