<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\RegisterNewUserFormRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_user()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif'
        ];

        $authController = new AuthController(
            $this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'),
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $authController->register(new RegisterNewUserFormRequest($userInfo));

        $this->assertDatabaseHas('users', [
            'username' => $userInfo['username'],
            'email' => $userInfo['email'],
        ]);
    }

    /** @test */
    public function registering_a_new_user_should_return_a_token()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif'
        ];

        $authController = new AuthController(
            $this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'),
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $response = $authController->register(new RegisterNewUserFormRequest($userInfo));

        $this->assertArrayHasKey('access_token', $response->getData(true));
        $this->assertArrayHasKey('token_type', $response->getData(true));
        $this->assertArrayHasKey('expires_in', $response->getData(true));
    }

    /** @test */
    public function it_throws_an_exception_when_it_can_not_create_a_user()
    {
        $userRepository = Mockery::mock($this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'));

        $userRepository->shouldReceive('create')->once()->andThrow(new \Exception('You shall not pass!', 9001));
        
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif'
        ];

        $authController = new AuthController(
            $userRepository,
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $response = $authController->register(new RegisterNewUserFormRequest($userInfo));

        $this->assertArrayHasKey('error', $response->getData(true));
        $this->assertEquals('There was an error creating the account', $response->getData()->error);
        $this->assertEquals(500, $response->getStatusCode());
    }

    /** @test */
    public function it_can_authenticate_an_existing_user()
    {
        $user = factory(User::class)->create();

        $authController = new AuthController(
            $this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'),
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $response = $authController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]));

        $this->assertAuthenticated();
        $this->assertArrayHasKey('access_token', $response->getData(true));
        $this->assertArrayHasKey('token_type', $response->getData(true));
        $this->assertArrayHasKey('expires_in', $response->getData(true));
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_credentials_are_incorrect()
    {
        $user = factory(User::class)->create();

        $authController = new AuthController(
            $this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'),
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $response = $authController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]));
        
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertArrayHasKey('error', $response->getData(true));
        $this->assertEquals('Unauthorized', $response->getData()->error);
    }

    /** @test */
    public function it_can_destroy_a_user_token()
    {
        $user = factory(User::class)->create();

        $authController = new AuthController(
            $this->app->make('App\Repositories\Interfaces\UserRepositoryInterface'),
            $this->app->make('Illuminate\Contracts\Auth\Guard')
        );

        $authController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]));

        // User is authenticated.
        $this->assertAuthenticated();

        // Invalidate the user.
        $authController->logout();

        // User should no longer be authenticated.
        $this->isFalse($this->isAuthenticated());
    }
}
