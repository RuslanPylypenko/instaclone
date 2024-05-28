<?php

namespace Tests\Unit\Models\User;

use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use Tests\TestCase;

class UserEntityTest extends TestCase
{
    public function test_create_user(): void
    {
        /** @var UserEntity $user */
        $user = UserEntity::make([
            'first_name'    => $firstName = 'Alex',
            'last_name'     => $lastName = 'Dron',
            'nick'          => $nick = 'agent007',
            'email'         => $email = 'app@test.mail',
            'password'      => $password = 'password',
            'birth_date'    => $date = (new \DateTime('22-08-2004')),
            'confirm_token' => null,
            'status'        => $status = UserStatus::NEW->value,
        ]);

        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($nick, $user->getNick());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($date, $user->getBirthDate());
        $this->assertNull($user->getConfirmToken());
        $this->assertEquals($status, $user->getStatus());
    }
}
