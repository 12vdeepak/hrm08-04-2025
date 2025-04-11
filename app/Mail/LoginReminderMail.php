<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;

    public function __construct($fullName)
    {
        $this->fullName = $fullName;
    }

    public function build()
    {
        return $this->subject('Reminder: Please Log in to HRMS & Teams')
            ->cc([
                'hr@quantumitinnovation.com',
                'mansi@quantumitinnovation.com',
                'sanchitha@quantumitinnovation.com',
            ])
            ->view('emails.login_reminder')
            ->with([
                'fullName' => $this->fullName,
            ]);
    }
}
