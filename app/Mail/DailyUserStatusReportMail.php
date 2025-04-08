<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyUserStatusReportMail extends Mailable
{
   use Queueable, SerializesModels;

    public $absentUsers;
    public $leaveUsers;

    public function __construct($absentUsers, $leaveUsers)
    {
        $this->absentUsers = $absentUsers;
        $this->leaveUsers = $leaveUsers;
    }

    public function build()
    {
        return $this->subject('Daily Attendance Status Report')
                    ->view('emails.daily_user_status_report');
    }
}
