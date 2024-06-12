<?php

namespace Tests\Unit\UseCases\User;

use App\Mail\Auth\ConfirmEmail;
use App\Models\User\UserEntity;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use App\Services\PasswordHasher;
use App\Services\Tokenizer;
use App\UseCases\User\SignUp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailer;
use Mockery;
use Tests\TestCase;


class SignUpTest extends TestCase
{

    protected UsersRepository $usersRepository;
    protected Tokenizer $tokenizer;
    protected PasswordHasher $passwordHasher;
    protected Mailer $mailer;
    protected SignUp $signUp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepository = Mockery::mock(UsersRepository::class);
        $this->tokenizer = new Tokenizer();
        $this->passwordHasher = new PasswordHasher();


        // Мокінг для мейлера
        $mailer = Mockery::mock(Mailer::class);
        $mailer->shouldReceive('to')
            ->with('app1@test.emails')
            ->andReturn($mailer);
        $mailer->shouldReceive('send')
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof ConfirmEmail;
            }))
            ->andReturnNull();


        $this->signUp = new SignUp(
            $this->usersRepository,
            $this->tokenizer,
            $this->passwordHasher,
            $mailer
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_request(): void
    {

        $user = $this->signUp->request([
            'first_name' => $firstName = 'Alex',
            'last_name'  => $lastName = 'Dron',
            'nick'       => $nick = 'agent007',
            'email'      => $email = 'app1@test.emails',
            'password'   => $password = 'password',
            'birth_date' => $date = (new \DateTime('22-08-2004')),
            'status'     => UserStatus::WAIT,
        ]);

        $this->assertEquals($firstName, $user->first_name);
        $this->assertEquals($lastName, $user->last_name);
        $this->assertEquals($nick, $user->nick);
        $this->assertEquals($email, $user->email);
        $this->assertTrue($this->passwordHasher->isValid($password, $user->getPassword()));
        $this->assertEquals($date, $user->birth_date);
        $this->assertNotNull($user->confirm_token);
        $this->assertTrue($user->isWait());
    }

    public function test_confirm(): void
    {
        $user = UserEntity::create([
            'first_name' => $firstName = 'Alex',
            'last_name'  => $lastName = 'Dron',
            'nick'       => $nick = 'agent008',
            'email'      => $email = 'app2@test.emails',
            'password'   => $password = 'password',
            'birth_date' => $date = (new \DateTime('22-08-2004')),
            'status'     => UserStatus::WAIT,
        ]);
        $user->confirm_token = $token = 'token';
        $this->usersRepository->shouldReceive('getByConfirmToken')
            ->with($token)
            ->andReturn($user);

        $this->signUp->confirm($token);

        $this->assertNull($user->confirm_token);
        $this->assertTrue($user->isActive());
    }

    public function test_confirm_active(): void
    {
        $user = UserEntity::create([
            'first_name' => $firstName = 'Alex',
            'last_name'  => $lastName = 'Dron',
            'nick'       => $nick = 'agent003',
            'email'      => $email = 'app3@test.emails',
            'password'   => $password = 'password',
            'birth_date' => $date = (new \DateTime('22-08-2004')),
            'status'     => UserStatus::WAIT,
        ]);
        $user->confirm_token = $token = 'token';
        $this->usersRepository->shouldReceive('getByConfirmToken')
            ->with($token)
            ->andReturn($user);

        $this->signUp->confirm($token);

        $this->expectExceptionMessage('User already confirmed.');

        $this->signUp->confirm($token);
    }
}
