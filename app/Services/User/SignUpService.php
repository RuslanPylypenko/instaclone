<?php

namespace App\Services\User;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\Hash;

class SignUpService
{
    public function __construct(
        private UsersRepository $usersRepository,
    ) {
    }

    public function signUpRequest(array $data): void
    {
        UserEntity::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'] ?? null,
            'nick'       => $data['email'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'birth_date' => $data['birth_date'],
            'status'     => UserStatus::NEW->value,
        ]);

        //event
    }

    public function signUpConfirm(string $token): void
    {
        $user = $this->usersRepository->getByConfirmToken($token);
        $user->confirm();
        $user->save();

        //event
    }
}
