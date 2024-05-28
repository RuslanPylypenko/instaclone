<?php

namespace App\Services\User;

use App\Events\UserConfirmedEvent;
use App\Events\UserRegisteredEvent;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SignUpService
{
    public function __construct(
        private UsersRepository $usersRepository,
        private ConfirmTokenGenerator $confirmTokenGenerator,
        private EventDispatcherInterface $eventDispatcher,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function signUpRequest(array $data): void
    {
        $user = UserEntity::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'] ?? null,
            'nick'          => $data['email'],
            'email'         => $data['email'],
            'password'      => $this->passwordHasher->hash($data['password']),
            'birth_date'    => $data['birth_date'],
            'confirm_token' => $this->confirmTokenGenerator->generate(),
            'status'        => UserStatus::NEW->value,
        ]);

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));
    }

    public function signUpConfirm(string $token): void
    {
        $user = $this->usersRepository->getByConfirmToken($token);
        $user->confirm();
        $user->save();

        $this->eventDispatcher->dispatch(new UserConfirmedEvent($user));
    }
}
