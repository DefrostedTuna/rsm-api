<?php

namespace Tests\Feature\Routes\Auth;

use App\Events\Auth\Verified;
use App\Models\User;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerificationRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authentication guard to be used.
     *
     * @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->auth = $this->app->make(Guard::class);
    }

    /** @test */
    public function it_can_verify_a_users_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->get($user->generateVerificationUrl());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Email has been successfully verified.',
        ]);
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_signature_is_invalid()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->get($user->generateVerificationUrl() . 'some-extra-data');

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid signature.',
        ]);
    }

    /** @test */
    public function it_will_only_verify_an_email_once()
    {
        $user = factory(User::class)->create(); // Email already verified.

        $response = $this->get($user->generateVerificationUrl());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Email has already been verified.',
        ]);
    }

    /** @test */
    public function it_will_emit_the_verified_event()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        Event::fake();
        
        $response = $this->get($user->generateVerificationUrl());

        Event::assertDispatched(Verified::class);
    }

    /** @test */
    public function it_can_resend_a_verification_email_if_the_user_is_authenticated()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        $token = $this->auth->fromUser($user);

        $response = $this->post('/api/email/resend', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Verification email has been sent.',
        ]);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function it_will_not_resend_a_verification_to_a_user_that_is_already_verified()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $token = $this->auth->fromUser($user);

        $response = $this->post('/api/email/resend', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Email has already been verified.',
        ]);
        Notification::assertNotSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function it_requires_an_authenticated_user_to_resend_a_verification_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
    
        $response = $this->post('/api/email/resend');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Token not provided',
        ]);
    }
}
