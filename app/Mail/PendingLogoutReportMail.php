<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Daily Logout Pending Employees report')
            ->to(['deepak.quantumitinnovation@gmail.com'])
            ->view('emails.daily_logout_pending_report')
            ->with(['pendingUsers' => $this->pendingUsers]);
    }
}
