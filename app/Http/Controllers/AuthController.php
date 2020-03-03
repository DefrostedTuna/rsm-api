<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserService;
use App\Events\Auth\Registered;
use App\Http\Requests\RegisterNewUserFormRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWT;

class AuthController extends Controller
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
     * Instance of the User Service to be used.
     *
     * @var \App\Contracts\Services\UserService
     */
    protected $userService;

    /**
     * Instance of the authentication provider.
     *
     * This is type-hinted as JWTGuard alongside Illuminate\Contracts\Auth\Guard
     * because tymon/jtw-auth has a class structure that does not completely fit
     * the default Guard contract.
     *
     * @var \Illuminate\Contracts\Auth\Guard|\Tymon\JWTAuth\JWTGuard
     */
    protected $auth;

    /**
     * Create a new AuthController instance.
     *
     * @param  \App\Contracts\Services\UserService  $user
     * @param  \Illuminate\Contracts\Auth\Guard     $auth
     *
     * @return void
     */
    public function __construct(UserService $user, Guard $auth)
    {
        $this->userService = $user;
        $this->auth = $auth;

        // $this->middleware('auth:api', ['except' => ['login']]);
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
     * Registers a new user and proceeds to log them in by issuing a JWT in response.
     *
     * @param  \App\Http\Requests\RegisterNewUserFormRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterNewUserFormRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create($request->only([
                'username',
                'email',
                'password',
            ]));

            event(new Registered($user));
                
            return $this->login($request);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'There was an error creating the account',
            ], 500);
        }
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
                'error' => 'Too many login attempts',
            ], 400);
        }

        $token = $this->auth->attempt($credentials);

        if (! $token) {
            $this->incrementLoginAttempts($request);

            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        // The token will be auto-magically retrieved from the request.
        // We also want to blacklist the token so that it may not be used in the future.
        $this->auth->logout(true);

        return new JsonResponse(['message' => 'Success'], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return new JsonResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60,
        ], 200);
    }
}
