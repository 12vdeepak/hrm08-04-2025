<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Holiday;

class FestivalGreetingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var User|null */
    public $user;

    /** @var Holiday */
    public $festival;

    /**
     * Create a new message instance.
     *
     * @param User|null $user
     * @param Holiday   $festival
     */
    public function __construct(?User $user, Holiday $festival)
    {
        $this->user = $user;
        $this->festival = $festival;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Festival Greetings - ' . $this->festival->occasion;

        // Use a general name if no user was provided (for HR copy)
        $name = $this->user ? $this->user->name : 'Team Quantum IT Innovation';

        return $this->subject($subject)
                    ->view('emails.festival.greeting')
                    ->with([
                        'name' => $name,
                        'festival' => $this->festival,
                    ]);
    }
}
