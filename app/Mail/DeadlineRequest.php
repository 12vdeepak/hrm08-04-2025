<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeadlineRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $timeTracker;

    /**
     * Create a new message instance.
     */
    public function __construct($timeTracker)
    {
        $this->timeTracker = $timeTracker;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Deadline Extension Request - Time Tracker')
                    ->view('emails.deadline_request')
                    ->with([
                        'timeTracker' => $this->timeTracker,
                        'user' => $this->timeTracker->user,
                        'project' => $this->timeTracker->project,
                        'updateUrl' => route('ba.update.new.deadline.form', $this->timeTracker->id)
                    ]);
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
