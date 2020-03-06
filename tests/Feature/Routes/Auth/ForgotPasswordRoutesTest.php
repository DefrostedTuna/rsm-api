<?php

namespace Tests\Feature\Routes\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_send_a_password_reset_email()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $response = $this->post('/api/password/email', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'A password reset email has been sent.',
        ]);
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function it_will_return_a_success_response_even_if_the_email_is_not_valid()
    {
        Notification::fake();

        $response = $this->post('/api/password/email', [
            'email' => 'invalid@email.com',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'A password reset email has been sent.',
        ]);
        Notification::assertNothingSent();
    }
}
