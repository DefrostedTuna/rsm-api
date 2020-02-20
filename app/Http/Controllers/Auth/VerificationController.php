<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\Verified;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Instance of the User Repository.
     *
     * @var \App\Repositories\Interfaces\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->userRepository->findOrFail($request->route('id'));

            if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

            if ($user->hasVerifiedEmail()) {
                return new JsonResponse([
                    'message' => 'Email has already been verified',
                ], 200);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return new JsonResponse([
                'message' => 'Email has been successfully verified',
            ], 200);
        } catch (\Exception $e) {
            throw new AuthorizationException;
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        /** @var \App\Models\User */
        if (! $user = $request->user()) {
            // This is a failsafe.
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return new JsonResponse([
                'error' => 'Email has already been verified',
            ], 403);
        }

        $user->sendEmailVerificationNotification();

        return new JsonResponse([
            'message' => 'Success',
        ], 200);
    }
}
