<?php

namespace App\Contracts\Services;

interface AuthService
{
    /**
     * Attempt to authenticate the user in with the given credentials.
     *
     * @param  array  $credentials
     *
     * @return array ['access_token' => string, 'expires_in' => int]
     */
    public function attemptLoginWithCredentials(array $credentials): array;

    /**
     * Invalidate the user token.
     *
     * @param  bool  $forceForever
     * 
     * @return void
     */
    public function logout(bool $forceForever): void;
}