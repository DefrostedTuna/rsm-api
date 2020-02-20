<?php

namespace App\Contracts\Auth;

interface MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool;

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified(): bool;

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void;

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification(): string;

    /**
     * Generate a temporary signed URL to be used for verification.
     *
     * @return string
     */
    public function generateVerificationUrl(): string;

    /**
     * Sets the Verification URL as a parameter for the front end route.
     *
     * @param  string  $verificationUrl
     *
     * @return string
     */
    public function generateFrontendVerificationUrl(string $verificationUrl): string;
}
