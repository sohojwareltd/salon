@php
    $layoutComponent = match(request()->segment(1)) {
        'customer-dashboard' => 'customer-dashboard',
        'provider-dashboard' => 'provider-dashboard',
        'salon-dashboard' => 'salon-dashboard',
        default => 'customer-dashboard',
    };
@endphp

<x-dynamic-component :component="$layoutComponent" title="Notifications">
<style>

    
    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .notifications-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 32px;
        border-radius: 20px;
        margin-bottom: 24px;
        color: white;
    }

    .notification-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        cursor: pointer;
        border-left: 4px solid transparent;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .notification-card.unread {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-left-color: #3b82f6;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;color:#fff">
                    <i class="bi bi-bell-fill me-2"></i>Notifications
                </h2>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;color:#fff">
                    {{ $unreadCount }} unread notification{{ $unreadCount != 1 ? 's' : '' }}
                </p>
            </div>
            <div style="display: flex; gap: 12px;">
                @if($unreadCount > 0)
                <button onclick="markAllRead()" class="btn-action btn-primary">
                    <i class="bi bi-check-all me-2"></i>Mark All Read
                </button>
                @endif
                <button onclick="deleteAll()" class="btn-action btn-danger">
                    <i class="bi bi-trash me-2"></i>Delete All
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    @forelse($notifications as $notification)
        <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" 
             onclick="handleNotificationClick({{ $notification->id }}, '{{ $notification->url }}')">
            <div style="display: flex; gap: 16px; align-items: start;">
                <div class="notification-icon" style="background: linear-gradient(135deg, {{ $notification->color }}, {{ $notification->color }}dd);">
                    <i class="bi {{ $notification->icon }}"></i>
                </div>
                
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <h5 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">
                            {{ $notification->title }}
                            @if(!$notification->is_read)
                                <span style="display: inline-block; width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; margin-left: 8px;"></span>
                            @endif
                        </h5>
                        <button onclick="event.stopPropagation(); deleteNotification({{ $notification->id }})" 
                                style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px 8px;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    
                    <p style="font-size: 14px; color: #64748b; margin-bottom: 12px;">
                        {{ $notification->message }}
                    </p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 12px; color: #94a3b8;">
                            <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </span>
                        
                        @if(!$notification->is_read)
                        <button onclick="event.stopPropagation(); markAsRead({{ $notification->id }})" 
                                style="font-size: 12px; color: #872341; font-weight: 600; background: none; border: none; cursor: pointer;">
                            Mark as read
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px;">
            <i class="bi bi-bell-slash" style="font-size: 64px; color: #cbd5e1; margin-bottom: 16px;"></i>
            <h3 style="font-size: 20px; font-weight: 700; color: #64748b; margin-bottom: 8px;">No Notifications</h3>
            <p style="font-size: 14px; color: #94a3b8; margin: 0;">You're all caught up! Check back later for updates.</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div style="margin-top: 32px;">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
    // Handle notification click
    function handleNotificationClick(notificationId, url) {
        fetch(`/{{ request()->segment(1) }}/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (url) {
                window.location.href = url;
            } else {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Mark as read
    function markAsRead(notificationId) {
        fetch(`/{{ request()->segment(1) }}/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    // Mark all as read
    function markAllRead() {
        if (!confirm('Mark all notifications as read?')) return;
        
        fetch('/{{ request()->segment(1) }}/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete notification
    function deleteNotification(notificationId) {
        if (!confirm('Delete this notification?')) return;
        
        fetch(`/{{ request()->segment(1) }}/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete all notifications
    function deleteAll() {
        if (!confirm('Delete all notifications? This action cannot be undone.')) return;
        
        fetch('/{{ request()->segment(1) }}/notifications', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</x-dynamic-component>
