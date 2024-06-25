<?php

namespace App\Mail\Auth;

use App\Models\User\UserEntity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly UserEntity $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.confirm',
            with: [
                'url' => route('register.confirm', ['token' => $this->user->confirm_token]),
            ]
        );
    }
}
