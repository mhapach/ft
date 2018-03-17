<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    // User data.
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param \App\User $user
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
        // Create activation link.
        $activationLink = route('activation', [
            'id' => $this->user->id,
            'token' => md5($this->user->email)
        ]);

        return $this->subject(trans('Fourthtour activation link'))
            ->view('emails.activate')->with([
                'link' => $activationLink
            ]);
    }
}