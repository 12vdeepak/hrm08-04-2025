<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingLogoutReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pendingUsers;

    /**
     * Create a new message instance.
     */
    public function __construct($pendingUsers)
    {
        $this->pendingUsers = $pendingUsers;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pending Logout Report Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pending_logout_report',
        );
    }

    public function build()
    {
        return $this->subject('Daily Logout Pending Employees report')
            ->to(['hr@quantumitinnovation.com', 'mansi@quantumitinnovation.com', 'sanchitha@quantumitinnovation.com'])
            ->view('emails.daily_logout_pending_report')
            ->with(['pendingUsers' => $this->pendingUsers]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
