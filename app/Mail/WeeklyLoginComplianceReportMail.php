<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyLoginComplianceReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportRows;
    public $reportMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($reportRows, $reportMessage = null)
    {
        $this->reportRows = $reportRows;
        $this->reportMessage = $reportMessage ?? 'Please find below the weekly login compliance report for employees who did not complete 9 hours on any day:';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Weekly Login Compliance Report')
            ->view('emails.weekly_login_compliance_report')
            ->with([
                'reportRows' => $this->reportRows,
                'reportMessage' => $this->reportMessage,
            ]);
    }
} 