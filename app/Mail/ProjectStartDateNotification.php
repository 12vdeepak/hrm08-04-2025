<?php

namespace App\Mail;

use App\Models\TimeTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectStartDateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $timeTracker;

    public function __construct(TimeTracker $timeTracker)
    {
        $this->timeTracker = $timeTracker;
    }

    public function build()
    {
        return $this->subject('Project Start Date Required - Time Tracker Entry')
                    ->view('emails.ba_notification')
                    ->with([
                        'timeTracker' => $this->timeTracker,
                        'user' => $this->timeTracker->user,
                        'project' => $this->timeTracker->project,
                        'updateUrl' => route('ba.update.project.date', $this->timeTracker->id)
                    ]);
    }
}