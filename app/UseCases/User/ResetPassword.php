<?php

namespace App\UseCases\User;

use App\Mail\Auth\ConfirmEmail;
use App\Mail\Auth\ResetPasswordEmail;
use App\Models\User\UserEntity;
use App\Services\Tokenizer;
use Illuminate\Mail\Mailer;

class ResetPassword
{
    public function __construct(
        private Tokenizer $tokenizer,
        private Mailer $mailer,
    ) {
    }

    public function request(UserEntity $user)
    {
        $user->setResetPasswordToken($this->tokenizer->generate());

        $this->mailer->to($user->email)->send(new ResetPasswordEmail($user));
    }
}
