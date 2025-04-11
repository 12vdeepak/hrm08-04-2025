<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateMarkReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;

    /**
     * Create a new message instance.
     */
    public function __construct($fullName)
    {
        $this->fullName = $fullName;
    }

    public function build()
    {
        return $this->subject('Marked Late Today')
            ->view('emails.late_mark_reminder')
            ->with([
                'fullName' => $this->fullName,
            ]);
    }
}
