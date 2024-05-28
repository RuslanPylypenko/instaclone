<?php

namespace App\UseCases\User;

use App\Events\UserConfirmedEvent;
use App\Events\UserRegisteredEvent;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use App\Services\PasswordHasher;
use App\Services\Tokenizer;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SignUp
{
    public function __construct(
        private UsersRepository          $usersRepository,
        private Tokenizer                $tokenizer,
        private EventDispatcher     $eventDispatcher,
        private PasswordHasher           $passwordHasher,
    ) {
    }

    public function request(array $data): UserEntity
    {
        $user = UserEntity::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'] ?? null,
            'nick'          => $data['nick'],
            'email'         => $data['email'],
            'password'      => $this->passwordHasher->hash($data['password']),
            'birth_date'    => $data['birth_date'],
            'confirm_token' => $this->tokenizer->generate(),
            'status'        => UserStatus::NEW->value,
        ]);

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));

        return $user;
    }

    public function confirm(string $token): void
    {
        $user = $this->usersRepository->getByConfirmToken($token);
        $user->confirm();
        $user->save();

        $this->eventDispatcher->dispatch(new UserConfirmedEvent($user));
    }
}
