<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * The maximum number of login attempts to allow.
     *
     * @var integer
     */
    protected $maxAttempts = 5;

    /**
     * The number of minutes to throttle for.
     *
     * @var integer
     */
    protected $decayMinutes = 1;

    /**
     * The instance of the Auth Service.
     *
     * @var \App\Contracts\Services\AuthService
     */
    protected $authService;

    /**
     * Create a new LoginController instance.
     *
     * @param  \App\Contracts\Services\AuthService  $authService
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return new JsonResponse([
                'success' => false,
                'message' => 'Too many authentication attempts.',
            ], 400);
        }

        try {
            $data = $this->authService->attemptLoginWithCredentials($credentials);

            return new JsonResponse([
                'success' => true,
                'message' => 'Successfully authenticated.',
                'data' => [
                    'access_token' => $data['access_token'],
                    'token_type' => 'bearer',
                    'expires_in' => $data['expires_in'],
                ],
            ], 200);
        } catch (\Exception $e) {
            $this->incrementLoginAttempts($request);
            
            return new JsonResponse([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }
    }

    /**
     * Invalidate the user token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout(true);

        return new JsonResponse([
            'success' => true,
            'message' => 'The user has successfully been logged out.',
        ], 200);
    }
}
