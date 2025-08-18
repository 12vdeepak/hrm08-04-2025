<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class EmployeeActivityReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $workedHours;
    public $remainingHours;
    public $date;
    public $currentTime;

    public function __construct(User $user, $workedHours, $remainingHours, Carbon $date)
    {
        $this->user = $user;
        $this->workedHours = $workedHours;
        $this->remainingHours = $remainingHours;
        $this->date = $date;
        $this->currentTime = Carbon::now()->format('H:i');
    }

    public function build()
    {
        return $this->subject('HRM Activity Reminder - Complete Your 9 Hours Today')
                    ->view('emails.employee-activity-reminder');
    }
}
