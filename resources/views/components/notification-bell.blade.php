<div style="position: relative; cursor: pointer;" id="notificationBell">
    <div style="width: 42px; height: 42px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
        <i class="bi bi-bell-fill" style="font-size: 18px; color: #64748b;"></i>
    </div>
    {{-- Badge --}}
    <span id="notificationBadge" style="position: absolute; top: -4px; right: -4px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 10px; min-width: 18px; text-align: center; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4); {{ $unreadCount == 0 ? 'display:none;' : '' }}">
        {{ $unreadCount }}
    </span>
    
    {{-- Notification Dropdown --}}
    <div id="notificationDropdown" style="display: none; position: absolute; top: 56px; right: 0; width: 380px; background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); z-index: 1000; max-height: 500px; overflow: hidden;">
        {{-- Header --}}
        <div style="padding: 20px 24px; border-bottom: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">
                Notifications
            </h6>
            <div style="display: flex; gap: 8px;">
                <span id="unreadCountText" style="font-size: 12px; color: #64748b; font-weight: 600;">
                    {{ $unreadCount }} New
                </span>
                @if($unreadCount > 0)
                <button onclick="markAllAsRead()" style="font-size: 12px; color: #872341; font-weight: 600; background: none; border: none; cursor: pointer; padding: 0;">
                    Mark all read
                </button>
                @endif
            </div>
        </div>
        
        {{-- Notification Items --}}
        <div id="notificationList" style="max-height: 400px; overflow-y: auto;">
            @forelse($notifications as $notification)
                <div class="notification-item" data-id="{{ $notification->id }}" data-url="{{ $notification->url }}" 
                     style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s; {{ !$notification->is_read ? 'background: #f0f9ff;' : 'background: white;' }}"
                     onclick="handleNotificationClick({{ $notification->id }}, '{{ $notification->url }}')">
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, {{ $notification->color }}, {{ $notification->color }}dd); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi {{ $notification->icon }}" style="color: white; font-size: 18px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <p style="font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 4px 0;">
                                {{ $notification->title }}
                            </p>
                            <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0;">
                                {{ $notification->message }}
                            </p>
                            <span style="font-size: 11px; color: #94a3b8;">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 40px 24px; text-align: center;">
                    <i class="bi bi-bell-slash" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                    <p style="font-size: 14px; color: #64748b; margin: 0;">No notifications yet</p>
                </div>
            @endforelse
        </div>
        
        {{-- Footer --}}
        @if($notifications->count() > 0)
        <div style="padding: 16px 24px; border-top: 2px solid #f1f5f9; text-align: center;">
            <a href="/{{ request()->segment(1) }}/notifications" style="font-size: 14px; font-weight: 600; color: #872341; text-decoration: none;">
                View All Notifications
            </a>
        </div>
        @endif
    </div>
</div>

<script>
let notificationDropdownOpen = false;

// Toggle Dropdown
document.getElementById('notificationBell').addEventListener('click', function(e) {
    e.stopPropagation();
    notificationDropdownOpen = !notificationDropdownOpen;
    document.getElementById('notificationDropdown').style.display = notificationDropdownOpen ? 'block' : 'none';
    
    if (notificationDropdownOpen) {
        loadLatestNotifications();
    }
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (notificationDropdownOpen && !document.getElementById('notificationBell').contains(e.target)) {
        notificationDropdownOpen = false;
        document.getElementById('notificationDropdown').style.display = 'none';
    }
});

// Load latest notifications via AJAX
function loadLatestNotifications() {
    const prefix = '{{ request()->segment(1) }}';
    fetch(`/${prefix}/notifications/latest`)
        .then(response => response.json())
        .then(data => {
            updateNotificationUI(data.notifications, data.unreadCount);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Handle notification click
function handleNotificationClick(notificationId, url) {
    const prefix = '{{ request()->segment(1) }}';
    fetch(`/${prefix}/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateBadge(data.unreadCount);
        if (url) {
            window.location.href = url;
        }
    })
    .catch(error => console.error('Error marking as read:', error));
}

// Mark all as read
function markAllAsRead() {
    const prefix = '{{ request()->segment(1) }}';
    fetch(`/${prefix}/notifications/read-all`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateBadge(0);
        loadLatestNotifications();
    })
    .catch(error => console.error('Error marking all as read:', error));
}

// Update notification UI
function updateNotificationUI(notifications, unreadCount) {
    const listContainer = document.getElementById('notificationList');
    const unreadText = document.getElementById('unreadCountText');
    
    if (notifications.length === 0) {
        listContainer.innerHTML = `
            <div style="padding: 40px 24px; text-align: center;">
                <i class="bi bi-bell-slash" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                <p style="font-size: 14px; color: #64748b; margin: 0;">No notifications yet</p>
            </div>
        `;
    } else {
        listContainer.innerHTML = notifications.map(notif => `
            <div class="notification-item" data-id="${notif.id}" data-url="${notif.url || ''}" 
                 style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s; ${!notif.is_read ? 'background: #f0f9ff;' : 'background: white;'}"
                 onclick="handleNotificationClick(${notif.id}, '${notif.url || ''}')">
                <div style="display: flex; gap: 12px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, ${notif.color}, ${notif.color}dd); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="bi ${notif.icon}" style="color: white; font-size: 18px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 4px 0;">
                            ${notif.title}
                        </p>
                        <p style="font-size: 13px; color: #64748b; margin: 0 0 6px 0;">
                            ${notif.message}
                        </p>
                        <span style="font-size: 11px; color: #94a3b8;">${notif.created_at}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    unreadText.textContent = `${unreadCount} New`;
    updateBadge(unreadCount);
}

// Update badge
function updateBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

// Poll for new notifications every 30 seconds
setInterval(function() {
    if (!notificationDropdownOpen) {
        const prefix = '{{ request()->segment(1) }}';
        fetch(`/${prefix}/notifications/latest`)
            .then(response => response.json())
            .then(data => {
                updateBadge(data.unreadCount);
            })
            .catch(error => console.error('Error polling notifications:', error));
    }
}, 30000);
</script>
