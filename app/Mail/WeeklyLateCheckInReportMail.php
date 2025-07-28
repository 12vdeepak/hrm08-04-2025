<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyLateCheckInReportMail extends Mailable
{
    use Queueable, SerializesModels;

   public $data;

public function __construct(array $data)
{
    $this->data = $data;
}

public function build()
{
    return $this->subject('Weekly Late Check-In Report')
        ->view('emails.weekly-late-checkin-report');
}
}
