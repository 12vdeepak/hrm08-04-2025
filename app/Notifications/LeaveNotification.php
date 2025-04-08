<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Livewire\Livewire;


class LeaveNotification extends Notification
{
    use Queueable;
    public $leave_request;
    /**
     * Create a new notification instance.
     */
    public function __construct($leave_request)
    {
        $this->leave_request=$leave_request;
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
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line('You have a new leave request from '.$this->leave_request->user->name)
                    ->view('mail.leave_request', ['leave_request' => $this->leave_request])
                    ->subject('Leave Request');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'notification_type' => 'Leave Request',
            'user' => $this->leave_request->user->name,
        ];
    }
}
