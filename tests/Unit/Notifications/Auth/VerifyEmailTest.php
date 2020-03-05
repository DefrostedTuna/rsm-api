<?php

namespace Tests\Unit\Notifications\Auth;

use App\Models\User;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_the_verification_email()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'email_verified_at' => null,
        ]);

        $user->notify(new VerifyEmail());

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function it_sends_the_verification_email_as_a_query_parameter()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'email_verified_at' => null,
        ]);

        $user->notify(new VerifyEmail());

        // The full schema should be the following:
        // https://example.com/email/verify?verificationUrl=https://api.example.com/email/verification/{user}/{hash}?signature={sig}
        Notification::assertSentTo($user, VerifyEmail::class, function ($notification, $channels) use ($user) {
            $url = $notification->toMail($user)->actionUrl;

            return $user->generateFrontendVerificationUrl(
                $user->generateVerificationUrl()
            ) === $url;
        });
    }
}
