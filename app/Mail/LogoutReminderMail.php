<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LogoutReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;

    public function __construct($fullName)
    {
        $this->fullName = $fullName;
    }

    public function build()
    {
        return $this->subject('HRMS Logout Pending')
            ->cc([
                'deepak.quantumitinnovation@gmail.com',
                // 'mansi@quantumitinnovation.com',
                // 'sanchitha@quantumitinnovation.com',
            ])
            ->view('emails.logout_reminder')
            ->with([
                'fullName' => $this->fullName,
            ]);
    }
} 