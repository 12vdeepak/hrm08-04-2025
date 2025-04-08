<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\leaverequest;

class SendLeaveEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Mail::to($this->email['reporting_manager'])
            ->cc($this->email['hr_emails'])
            ->send(new leaverequest($this->email['details']));
    }
}
