<?php

use App\Models\Notification;

if (!function_exists('makeNotification')) {
    /**
     * Create a notification for a user
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type
     * @return Notification
     */
    function makeNotification($userId, $title, $message, $url = null, $type = 'booking')
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'type' => $type,
            'is_read' => false,
        ]);
    }
}

if (!function_exists('notifyUsers')) {
    /**
     * Create notifications for multiple users
     *
     * @param array $userIds
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type
     * @return void
     */
    function notifyUsers($userIds, $title, $message, $url = null, $type = 'booking')
    {
        foreach ($userIds as $userId) {
            makeNotification($userId, $title, $message, $url, $type);
        }
    }
}
