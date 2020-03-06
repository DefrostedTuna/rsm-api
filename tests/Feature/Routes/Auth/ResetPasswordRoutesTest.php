<?php

namespace Tests\Feature\Routes\Auth;

use App\Events\Auth\PasswordChanged;
use App\Models\User;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResetPasswordRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_reset_a_password()
    {
        $user = factory(User::class)->create();
        $broker = $this->app->make(PasswordBroker::class);
        $token = $broker->createToken($user);

        Event::fake();

        $response = $this->post('/api/password/reset', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Password has successfully reset.',
        ]);
        Event::assertDispatched(PasswordChanged::class);
    }

    /** @test */
    public function it_will_return_an_error_when_the_reset_cannot_be_completed()
    {
        $user = factory(User::class)->create();

        Event::fake();

        $response = $this->post('/api/password/reset', [
            'email' => $user->email,
            'token' => 'invalid-token',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(500);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'There was a problem resetting the password.',
        ]);
        Event::assertNotDispatched(PasswordChanged::class);
    }
}
