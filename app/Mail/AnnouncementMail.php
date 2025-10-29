<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Announcement */
    public $announcement;

    /** @var User|null */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Announcement $announcement
     * @param User|null $user
     */
    public function __construct(Announcement $announcement, ?User $user)
    {
        $this->announcement = $announcement;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->announcement->title ?? 'Announcement';
        $body = $this->announcement->announcement ?? '';

        return $this->subject($subject)
                    ->view('emails.announcement')
                    ->with([
                        'announcement' => $this->announcement,
                        'user' => $this->user,
                        'name' => $this->user ? $this->user->name : 'Team Quantum IT Innovation',
                        'body' => strip_tags($body),
                    ]);
    }
}
