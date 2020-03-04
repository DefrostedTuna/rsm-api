<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthService;
use App\Contracts\Services\UserService;
use App\Events\Auth\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterNewUserFormRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Instance of the User Service.
     *
     * @var \App\Contracts\Services\UserService
     */
    protected $userService;

    /**
     * Instance of the Auth Service.
     *
     * @var \App\Contracts\Services\AuthService
     */
    protected $authService;

    /**
     * Create a new RegisterController instance.
     *
     * @param  \App\Contracts\Services\UserService  $userService
     * @param  \App\Contracts\Services\AuthService  $authService
     *
     * @return void
     */
    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function register(RegisterNewUserFormRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create($request->validated());
            $credentials = $request->only(['email', 'password']);

            event(new Registered($user));

            $data = $this->authService->attemptLoginWithCredentials($credentials);

            return new JsonResponse([
                'access_token' => $data['access_token'],
                'token_type' => 'bearer',
                'expires_in' => $data['expires_in'],
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'There was an error creating the account',
            ], 500);
        }
    }
}
