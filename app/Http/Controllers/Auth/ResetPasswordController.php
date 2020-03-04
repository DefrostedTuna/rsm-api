<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Instance of the User Service.
     *
     * @var \App\Contracts\Services\UserService
     */
    protected $userService;

    /**
     * Instance of the Password Broker.
     *
     * @var \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected $broker;

    /**
     * Create a new ForgotPasswordController instance.
     *
     * @param  \App\Contracts\Services\UserService        $userService
     * @param  \Illuminate\Contracts\Auth\PasswordBroker  $broker
     *
     * @return void
     */
    public function __construct(UserService $userService, PasswordBroker $broker)
    {
        $this->userService = $userService;
        $this->broker = $broker;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = $this->broker->reset(
            $request->only(['email', 'password', 'password_confirmation', 'token']),
            function ($user, $password) {
                $this->userService->setPassword($user, $password);
            }
        );

        return $response == PasswordBroker::PASSWORD_RESET
                    ? $this->sendResetResponse()
                    : $this->sendResetFailedResponse();
    }

    /**
     * Get the response for a successful password reset.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Password has successfully reset',
        ], 200);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'There was a problem resetting the password',
        ], 500);
    }
}
