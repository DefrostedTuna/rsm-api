<?php

namespace App\Listeners\Auth;

use App\Contracts\Auth\MustVerifyEmail;
use App\Events\Auth\Registered;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\Auth\RegisteredUser  $event
     *
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $event->user->sendEmailVerificationNotification();
        }
    }
}
