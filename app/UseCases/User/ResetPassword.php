<?php

namespace App\UseCases\User;

use App\Mail\Auth\ResetPasswordEmail;
use App\Models\User\UserEntity;
use App\Repositories\UsersRepository;
use App\Services\PasswordHasher;
use App\Services\Tokenizer;
use Illuminate\Mail\Mailer;

class ResetPassword
{
    public function __construct(
        private UsersRepository $usersRepository,
        private Tokenizer $tokenizer,
        private Mailer $mailer,
    ) {
    }

    public function request(UserEntity $user)
    {
        $user->setResetPasswordToken($this->tokenizer->generate());

        $this->mailer->to($user->email)->send(new ResetPasswordEmail($user));
    }

    public function reset(string $token, string $password)
    {
        $user = $this->usersRepository->getByResetPasswordToken($token);
        $user->resetPassword($password, new PasswordHasher());
    }
}
