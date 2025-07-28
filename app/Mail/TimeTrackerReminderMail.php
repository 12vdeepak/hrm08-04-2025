<?php

namespace App\Mail;



use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TimeTrackerReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Reminder: Time Tracker Entry Incomplete')
                    ->view('emails.time_tracker_reminder')
                    ->with(['user' => $this->user]);
    }
}
