<?php

namespace Tests\Unit\Controllers\Auth;

use App\Contracts\Services\AuthService;
use App\Contracts\Services\UserService;
use App\Events\Auth\Registered;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\RegisterNewUserFormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_user()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
        ];

        $userService = $this->app->make(UserService::class);
        $authService = $this->app->make(AuthService::class);
        $registerController = new RegisterController($userService, $authService);

        /** @var \App\Http\Requests\RegisterNewUserFormRequest */
        $request = $this->mock(RegisterNewUserFormRequest::class, function ($mock) use ($userInfo) {
            $mock->shouldReceive('validated')->andReturn($userInfo);
            $mock->shouldReceive('only')->andReturn([
                'email' => $userInfo['email'],
                'password' => $userInfo['password'],
            ]);
        });

        $registerController->register($request);

        $this->assertDatabaseHas('users', [
            'username' => $userInfo['username'],
            'email' => $userInfo['email'],
        ]);
    }

    /** @test */
    public function registering_a_new_user_should_return_a_token_1()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
        ];

        $userService = $this->app->make(UserService::class);
        $authService = $this->app->make(AuthService::class);
        $registerController = new RegisterController($userService, $authService);

        /** @var \App\Http\Requests\RegisterNewUserFormRequest */
        $request = $this->mock(RegisterNewUserFormRequest::class, function ($mock) use ($userInfo) {
            $mock->shouldReceive('validated')->andReturn($userInfo);
            $mock->shouldReceive('only')->andReturn([
                'email' => $userInfo['email'],
                'password' => $userInfo['password'],
            ]);
        });

        $response = $registerController->register($request);

        $this->assertArrayHasKey('access_token', $response->getData(true));
        $this->assertArrayHasKey('token_type', $response->getData(true));
        $this->assertArrayHasKey('expires_in', $response->getData(true));
    }

    /** @test */
    public function it_fires_the_registered_event_when_a_user_is_created()
    {
        $this->expectsEvents(Registered::class);

        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
        ];

        $userService = $this->app->make(UserService::class);
        $authService = $this->app->make(AuthService::class);
        $registerController = new RegisterController($userService, $authService);

        /** @var \App\Http\Requests\RegisterNewUserFormRequest */
        $request = $this->mock(RegisterNewUserFormRequest::class, function ($mock) use ($userInfo) {
            $mock->shouldReceive('validated')->andReturn($userInfo);
            $mock->shouldReceive('only')->andReturn([
                'email' => $userInfo['email'],
                'password' => $userInfo['password'],
            ]);
        });

        $response = $registerController->register($request);

        $this->assertArrayHasKey('access_token', $response->getData(true));
        $this->assertArrayHasKey('token_type', $response->getData(true));
        $this->assertArrayHasKey('expires_in', $response->getData(true));
    }

    /** @test */
    public function it_throws_an_exception_when_it_can_not_create_a_user()
    {
        /** @var \Mockery\MockInterface|\App\Repositories\Interfaces\UserService */
        $userRepository = $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andThrow(new \Exception('You shall not pass!', 9001));
        });

        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
        ];

        $userService = $this->app->make(UserService::class);
        $authService = $this->app->make(AuthService::class);
        $registerController = new RegisterController($userService, $authService);

        /** @var \App\Http\Requests\RegisterNewUserFormRequest */
        $request = $this->mock(RegisterNewUserFormRequest::class, function ($mock) use ($userInfo) {
            $mock->shouldReceive('validated')->andReturn($userInfo);
            $mock->shouldReceive('only')->andReturn([
                'email' => $userInfo['email'],
                'password' => $userInfo['password'],
            ]);
        });

        $response = $registerController->register($request);

        $this->assertArrayHasKey('error', $response->getData(true));
        $this->assertEquals('There was an error creating the account', $response->getData()->error);
        $this->assertEquals(500, $response->getStatusCode());
    }
}