<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReportReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $fridayLeave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $fridayLeave = false)
    {
        $this->user = $user;
        $this->fridayLeave = $fridayLeave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Weekly Report Reminder')
            ->view('emails.weekly_report_reminder')
            ->with([
                'fridayLeave' => $this->fridayLeave
            ]);
    }
}
