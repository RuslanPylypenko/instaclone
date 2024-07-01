<?php

namespace App\UseCases\User;

use App\Mail\Auth\ConfirmEmail;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use App\Services\PasswordHasher;
use App\Services\Tokenizer;
use Illuminate\Mail\Mailer;

class SignUp
{
    public function __construct(
        private UsersRepository $usersRepository,
        private Tokenizer $tokenizer,
        private PasswordHasher $passwordHasher,
        private Mailer $mailer,
    ) {
    }

    public function request(array $data): UserEntity
    {
        /** @var UserEntity $user */
        $user = UserEntity::make([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'] ?? null,
            'nick'          => $data['nick'],
            'email'         => $data['email'],
            'password'      => $this->passwordHasher->hash($data['password']),
            'birth_date'    => $data['birth_date'],
            'confirm_token' => $this->tokenizer->generate(),
            'status'        => UserStatus::WAIT->value,
        ]);

        $this->mailer->to($user->email)->send(new ConfirmEmail($user));

        return $user;
    }

    public function confirm(string $token): void
    {
        $user = $this->usersRepository->getByConfirmToken($token);
        $user->confirm();
        $user->save();
    }
}
