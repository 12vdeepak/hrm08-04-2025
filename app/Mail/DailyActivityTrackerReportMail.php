<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyActivityTrackerReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportRows;
    public $body;

    public function __construct($reportRows, $body)
    {
        $this->reportRows = $reportRows;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject('Daily Activity Tracker Report')
                    ->view('emails.daily-activity-tracker-report');
    }
}
