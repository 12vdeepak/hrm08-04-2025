<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FestivalGreeting extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $festivalName;
    public $employeeName;
    public $startDate;
    public $endDate;
    public $type;

    // Add retry configuration to handle 421 errors
    public $tries = 3; // Retry up to 3 times
    public $backoff = [30, 60, 120]; // Wait 30s, then 60s, then 120s between retries

    public function __construct($festivalName, $employeeName, $startDate, $endDate, $type = 'greeting')
    {
        $this->festivalName = $festivalName;
        $this->employeeName = $employeeName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
    }

    public function build()
    {
        $subject = ($this->type === 'reminder')
            ? "Reminder: {$this->festivalName} Holiday Tomorrow"
            : "Happy {$this->festivalName}!";

        $template = ($this->type === 'reminder')
            ? 'emails.festival_reminder'
            : 'emails.festival_greeting';

        return $this->subject($subject)
            ->view($template);
    }
}
