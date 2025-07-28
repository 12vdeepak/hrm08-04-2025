<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyTimeTrackerHRReportMail extends Mailable
{
    use Queueable, SerializesModels;

   public $report;

public function __construct($report)
{
    $this->report = $report;
}

public function build()
{
    return $this->subject('Weekly Time Tracker Report')
        ->view('emails.weekly_hr_time_tracker_report');
}
}
