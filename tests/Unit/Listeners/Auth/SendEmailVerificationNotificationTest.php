<?php

namespace Tests\Unit\Listeners\Auth;

use App\Events\Auth\Registered;
use App\Listeners\Auth\SendEmailVerificationNotification;
use App\Models\User;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendEmailVerificationNotificationTest extends TestCase
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

        $event = new Registered($user);
        $listener = (new SendEmailVerificationNotification())->handle($event);

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
