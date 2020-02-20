<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  \App\Models\User  $user
     *
     * @return array
     */
    public function via($user)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user): \Illuminate\Notifications\Messages\MailMessage
    {
        $verificationUrl = $user->generateFrontendVerificationUrl(
            $user->generateVerificationUrl()
        );

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }
}
