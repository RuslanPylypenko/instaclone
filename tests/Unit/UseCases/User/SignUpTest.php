<?php

namespace Tests\Unit\UseCases\User;

use App\Events\UserRegisteredEvent;
use App\Models\User\UserStatus;
use App\Repositories\UsersRepository;
use App\Services\PasswordHasher;
use App\Services\Tokenizer;
use App\UseCases\User\SignUp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    protected UsersRepository $usersRepository;
    protected Tokenizer $tokenizer;
    protected PasswordHasher $passwordHasher;
    protected EventDispatcher $dispatcher;
    protected SignUp $signUp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepository = Mockery::mock(UsersRepository::class);
        $this->tokenizer = new Tokenizer();
        $this->passwordHasher = new PasswordHasher();
        $this->dispatcher = Mockery::mock(EventDispatcher::class);

        $this->signUp = new SignUp(
            $this->usersRepository,
            $this->tokenizer,
            $this->dispatcher,
            $this->passwordHasher
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_request(): void
    {
        $this->dispatcher
            ->shouldReceive('dispatch')
            ->with(Mockery::type(UserRegisteredEvent::class));

        $user = $this->signUp->request([
            'first_name' => $firstName = 'Alex',
            'last_name'  => $lastName = 'Dron',
            'nick'       => $nick = 'agent007',
            'email'      => $email = 'app1@test.emails',
            'password'   => $password = 'password',
            'birth_date' => $date = (new \DateTime('22-08-2004')),
            'status'     => UserStatus::WAIT->value,
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
}
