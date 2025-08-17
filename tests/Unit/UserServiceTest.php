<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $service;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(UserRepository::class);
        $this->service = new UserService($this->repository);
    }

    /** @test */
    public function login_successful_returns_user()
    {
        $user = new User(['password' => Hash::make('secret')]);

        $this->repository
            ->shouldReceive('findByLogin')
            ->with('test@example.com')
            ->andReturn($user);

        $result = $this->service->login('test@example.com', 'secret');

        $this->assertInstanceOf(User::class, $result);
    }

    /** @test */
    public function login_fails_with_invalid_password()
    {
        $this->repository
            ->shouldReceive('findByLogin')
            ->with('test@example.com')
            ->andReturn(new User(['password' => Hash::make('secret')]));

        $this->expectException(ValidationException::class);

        $this->service->login('test@example.com', 'wrong-password');
    }
}
