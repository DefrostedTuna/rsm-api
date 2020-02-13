<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterNewUserFormRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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
     * Instance of the User Repository to be used.
     *
     * @var \App\Repositories\Interfaces\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Instance of the authentication provider.
     * 
     * This is type-hinted as JWTGuard instead of Illuminate\Contracts\Auth\Guard 
     * because tymon/jtw-auth has a class structure that does not completely fit
     * the default Guard contract.
     *
     * @var \Tymon\JWTAuth\JWTGuard
     */
    protected $auth;

    /**
     * Create a new AuthController instance.
     *
     * @param  \Repositories\Interfaces\UserRepositoryInterface  $user
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     */
    public function __construct(UserRepositoryInterface $user, Guard $auth)
    {
        $this->userRepository = $user;
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
     * Undocumented function
     *
     * @param  \App\Http\Requests\RegisterNewUserFormRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterNewUserFormRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->userRepository->create($request->only([
                'username',
                'email',
                'password',
            ]));

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
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return new JsonResponse([
                'error' => 'Too many login attempts'
            ], 400);
        }

        $token = $this->auth->attempt($credentials);

        if (!$token) {
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
    public function logout(): \Illuminate\Http\JsonResponse
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
    protected function respondWithToken(string $token): \Illuminate\Http\JsonResponse
    {
        return new JsonResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ], 200);
    }
}
