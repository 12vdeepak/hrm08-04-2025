<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User; // <-- Make sure this is imported
use App\Models\Holiday;
use Illuminate\Contracts\Queue\ShouldQueue;

class FestivalGreetingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $festival;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Holiday $festival) // <-- Fix is here
    {
        $this->user = $user;
        $this->festival = $festival;
    }

    public function build()
    {
        return $this->subject('Festival Greetings - ' . $this->festival->occasion)
                    ->view('emails.festival.greeting');
    }
}
