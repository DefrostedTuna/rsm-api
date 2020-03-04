<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * 
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  \App\Models\User  $notifiable
     * 
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  \App\Models\User  $notifiable
     * 
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $expiryTime = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        $resetLink = $this->createResetLink($this->token, $notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetLink)
            ->line("This password reset link will expire in {$expiryTime} minutes.")
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Creates the password reset link to be sent to the user.
     *
     * @param  string  $token
     * @param  string  $email
     *
     * @return  string
     */
    protected function createResetLink(string $token, string $email): string
    {
        $email = urlencode($email);
        $baseUrl = config('frontend.url') . config('frontend.routes.password_reset');
        $queryString = "?token={$token}&email={$email}";

        return $baseUrl . $queryString;
    }
}
