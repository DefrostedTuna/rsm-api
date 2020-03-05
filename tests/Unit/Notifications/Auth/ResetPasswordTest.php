<?php

namespace Tests\Unit\Notifications\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_a_mail_notification()
    {
        /** @var \App\Models\User */
        $user = factory(User::class)->create();

        Notification::fake();

        $user->notify(new ResetPassword('this-is-my-token'));

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function it_uses_the_frontend_url_schema()
    {
        /** @var \App\Models\User */
        $user = factory(User::class)->create();

        $token = 'this-is-my-token';
        $email = urlencode($user->email);
        $baseUrl = config('frontend.url') . config('frontend.routes.password_reset');
        $queryString = "?token={$token}&email={$email}";
        $expectedRoute = $baseUrl . $queryString;

        Notification::fake();

        $user->notify(new ResetPassword($token));

        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function ($notification, $channels) use ($user, $expectedRoute) {
                $url = $notification->toMail($user)->actionUrl;

                return $url === $expectedRoute;
            }
        );
    }
}
