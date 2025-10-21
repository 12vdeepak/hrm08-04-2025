<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class BirthdayWishes extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyName = 'Quantum It Innovation';
        
        return $this->subject("Happy Birthday, {$this->user->name}!")
            ->view('emails.birthday')
            ->with([
                'name' => $this->user->name,
                'companyName' => $companyName,
                'birthday' => $this->user->date_of_birth,
            ]);
    }
}