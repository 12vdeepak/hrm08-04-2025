<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FestivalGreeting extends Mailable
{
    use Queueable, SerializesModels;

    public $festivalName;
    public $employeeName;
    public $startDate;
    public $endDate;

    public function __construct($festivalName, $employeeName, $startDate, $endDate)
    {
        $this->festivalName = $festivalName;
        $this->employeeName = $employeeName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function build()
    {
        return $this->subject("Happy {$this->festivalName}!")
            ->view('emails.festival_greeting');
    }
}
