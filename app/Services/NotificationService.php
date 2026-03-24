<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationService
{
    /**
     * Send an in-app notification to a logged-in user.
     */
    public function notify(int $userId, string $message, string $type = 'general'): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'type'    => $type,
            'is_read' => false,
        ]);
    }

    /**
     * Send an email notification (for anonymous users or as a bonus channel).
     */
    public function notifyByEmail(string $email, string $message, string $type = 'general'): void
    {
        // Only attempt if mail is configured
        if (config('mail.default') !== 'log' || app()->environment('production')) {
            Mail::to($email)->queue(new NotificationMail($message, $type));
        }
    }

    /**
     * Notify on complaint submission.
     */
    public function complaintSubmitted(int $userId, int $complaintId): void
    {
        $this->notify(
            $userId,
            "Your complaint #{$complaintId} has been received and is pending review.",
            'complaint'
        );
    }

    /**
     * Notify on complaint status change.
     */
    public function complaintStatusUpdated(int $userId, int $complaintId, string $status): void
    {
        $statusLabels = [
            'under_review' => 'is now under review',
            'resolved'     => 'has been resolved',
            'rejected'     => 'has been rejected',
            'pending'      => 'has been reset to pending',
        ];

        $label = $statusLabels[$status] ?? "status changed to {$status}";

        $this->notify(
            $userId,
            "Your complaint #{$complaintId} {$label}.",
            'complaint'
        );
    }

    /**
     * Notify on license application status change.
     */
    public function licenseStatusUpdated(int $userId, int $applicationId, string $status): void
    {
        $this->notify(
            $userId,
            "Your license application #{$applicationId} has been {$status}.",
            'license'
        );
    }

    /**
     * Broadcast a system-wide alert to multiple users.
     */
    public function broadcastAlert(array $userIds, string $message): void
    {
        $notifications = array_map(fn($id) => [
            'user_id'    => $id,
            'message'    => $message,
            'type'       => 'alert',
            'is_read'    => false,
            'created_at' => now(),
        ], $userIds);

        // Bulk insert — one query for all users
        Notification::insert($notifications);
    }
}