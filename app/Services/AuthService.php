<?php

namespace App\Services;

use App\Contracts\Services\AuthService as AuthServiceContract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Guard;

class AuthService implements AuthServiceContract
{
    /**
     * Instance of the Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard|\Tymon\JWTAuth\JWTGuard
     */
    protected $guard;

    /**
     * Create a new AuthService instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $guard
     * 
     * @return void
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Attempt to authenticate the user in with the given credentials.
     *
     * @param  array  $credentials
     *
     * @return array ['access_token' => string, 'expires_in' => int]
     * 
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @todo Check for 'new' device login attempts and send a notification to the user.
     */
    public function attemptLoginWithCredentials(array $credentials): array
    {
        $token = $this->guard->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        if (! $token) {
            throw new AuthorizationException('The credentials were invalid');
        }

        return [
            'access_token' => $token,
            'expires_in' => $this->getDefaultTTL() * 60, // Convert to seconds.
        ];
    }

    /**
     * Invalidate the user token.
     *
     * @param  bool  $forceForever
     * 
     * @return void
     */
    public function logout(bool $forceForever = false): void
    {
        $this->guard->logout($forceForever);
    }

    /**
     * The default TTL of the JWT token.
     *
     * @return int
     */
    protected function getDefaultTTL(): int
    {
        /** @var \Tymon\JWTAuth\Factory */
        $factory = $this->guard->factory();

        return $factory->getTTL();
    }
}
