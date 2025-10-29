<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class announcement extends Notification implements ShouldQueue
{
    use Queueable;
    public $announcement;
    /**
     * Create a new notification instance.
     */
    public function __construct($announcement)
    {
        $this->announcement=$announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = (string) ($this->announcement->title ?? 'Announcement');
        $body = (string) ($this->announcement->announcement ?? '');

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line(strip_tags($body))
            ->action('View Announcement', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'notification_type' => "announcement",
            'notification_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'announcement' => $this->announcement->announcement,
            'department' => $this->announcement->department,
        ];
    }
}
