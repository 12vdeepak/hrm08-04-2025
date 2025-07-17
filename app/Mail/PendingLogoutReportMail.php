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

    public function build()
    {
        return $this->subject('Daily Logout Pending Employees report')
            ->to(['hr@quantumitinnovation.com', 'mansi@quantumitinnovation.com', 'sanchitha@quantumitinnovation.com'])
            ->view('emails.daily_logout_pending_report')
            ->with(['pendingUsers' => $this->pendingUsers]);
    }

    
}
