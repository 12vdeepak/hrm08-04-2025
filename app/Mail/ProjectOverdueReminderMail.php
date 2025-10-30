<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\ProjectName;

class ProjectOverdueReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    public $thresholdMonths;
    public $startDate;
    public $deadlineDate;
    public $daysOverdue;

    public function __construct(User $user, ProjectName $project, int $thresholdMonths, string $startDate, string $deadlineDate, int $daysOverdue)
    {
        $this->user = $user;
        $this->project = $project;
        $this->thresholdMonths = $thresholdMonths;
        $this->startDate = $startDate;
        $this->deadlineDate = $deadlineDate;
        $this->daysOverdue = $daysOverdue;
    }

    public function build()
    {
        return $this->subject('Project overdue: ' . $this->project->name)
            ->view('emails.project-overdue-reminder');
    }
}


