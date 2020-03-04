<?php

namespace Tests\Unit\Controllers\Auth;

use App\Contracts\Services\UserService;
use App\Events\Auth\PasswordChanged;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\User;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_reset_a_password()
    {
        $user = factory(User::class)->create();
        $broker = $this->app->make(PasswordBroker::class);
        $token = $broker->createToken($user);

        /** @var \Illuminate\Http\Request */
        $request = $this->mock(
            $this->getResolvedClassName(Request::class),
            function ($mock) use ($user, $token) {
                $mock->shouldReceive('validate')->andReturn($user->toArray());
                $mock->shouldReceive('only')->andReturn([
                    'email' => $user->email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                    'token' => $token,
                ]);
            }
        );

        Event::fake();

        $userService = $this->app->make(UserService::class);
        $controller = new ResetPasswordController($userService, $broker);

        $response = $controller->reset($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            ['message' => 'Password has successfully reset'],
            $response->getData(true)
        );
        Event::assertDispatched(PasswordChanged::class);
    }

    /** @test */
    public function it_will_return_an_error_when_the_reset_cannot_be_completed()
    {
        $user = factory(User::class)->create();
        $token = 'this-is-my-token';

        $userService = $this->app->make(UserService::class);

        /** @var \Mockery\MockInterface|\Illuminate\Http\Request */
        $request = $this->mock(
            $this->getResolvedClassName(Request::class),
            function ($mock) use ($user, $token) {
                $mock->shouldReceive('validate')->andReturn($user->toArray());
                $mock->shouldReceive('only')->andReturn([
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
                'token' => $token,
            ]);
            }
        );

        /** @var \Mockery\MockInterface|\Illuminate\Contracts\Auth\PasswordBroker */
        $broker = $this->mock(
            $this->getResolvedClassName(PasswordBroker::class),
            function ($mock) use ($user) {
                $mock->shouldReceive('reset')->andReturn('You shall not pass!');
            }
        );
        
        $controller = new ResetPasswordController($userService, $broker);
        $response = $controller->reset($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(
            ['message' => 'There was a problem resetting the password'],
            $response->getData(true)
        );
    }
}
