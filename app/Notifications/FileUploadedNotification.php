<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FileUploadedNotification extends Notification
{
    use Queueable;

    protected $file;

    /**
     * Create a new notification instance.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable)
    {
        return ['database']; // You can also add 'mail' if needed
    }

    /**
     * Store notification in database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'notification_type' => 'File Upload',
            'message'           => 'A new file has been uploaded: ' . $this->file->original_name,
            'file_id'           => $this->file->id,
            'file_name'         => $this->file->original_name,
            'uploaded_at'       => $this->file->created_at->toDateTimeString(),
            'file_url'          => asset('storage/' . $this->file->file_path),
        ];
    }

    /**
     * Optional array format (used when calling $notification->toArray()).
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
