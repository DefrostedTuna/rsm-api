<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\User;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_send_a_password_reset_email()
    {
        $user = factory(User::class)->create();

        /** @var \Mockery\MockInterface|\Illuminate\Http\Request */
        $request = $this->mock(
            $this->getResolvedClassName(Request::class),
            function ($mock) use ($user) {
                $mock->shouldReceive('validate')->andReturn($user->toArray());
                $mock->shouldReceive('only')->andReturn([
                    'email' => $user->email,
                ]);
            }
        );

        /** @var \Mockery\MockInterface|\Illuminate\Contracts\Auth\PasswordBroker */
        $broker = $this->mock(
            $this->getResolvedClassName(PasswordBroker::class),
            function ($mock) {
                $mock->shouldReceive('sendResetLink')->andReturn(PasswordBroker::RESET_LINK_SENT);
            }
        );

        $controller = new ForgotPasswordController($broker);
        $response = $controller->sendResetLinkEmail($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'A password reset email has been sent',
        ]);
    }

    /** @test */
    public function it_will_return_a_success_response_even_if_the_email_is_not_valid()
    {
        /** @var \Mockery\MockInterface|\Illuminate\Http\Request */
        $request = $this->mock(
            $this->getResolvedClassName(Request::class),
            function ($mock) {
                $mock->shouldReceive('validate')->andReturn([
                    'email' => 'not-a-valid-email',
                ]);
                $mock->shouldReceive('only')->andReturn([
                    'email' => 'not-a-valid-email',
                ]);
            }
        );

        /** @var \Mockery\MockInterface|\Illuminate\Contracts\Auth\PasswordBroker */
        $broker = $this->mock(
            $this->getResolvedClassName(PasswordBroker::class),
            function ($mock) {
                $mock->shouldReceive('sendResetLink')->andReturn(PasswordBroker::RESET_LINK_SENT);
            }
        );

        $controller = new ForgotPasswordController($broker);
        $response = $controller->sendResetLinkEmail($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'A password reset email has been sent',
        ]);
    }
}
