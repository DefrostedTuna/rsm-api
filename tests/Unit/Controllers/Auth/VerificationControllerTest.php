<?php

namespace Tests\Unit\Controllers\Auth;

use App\Contracts\Services\UserService;
use App\Events\Auth\Verified;
use App\Http\Controllers\Auth\VerificationController;;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_verify_a_users_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        // Set up the route so that we can get the proper parameters from the request.
        $request = (new Request())->create('/', 'GET');
        $request->setRouteResolver(function () use ($request, $user) {
            $route = new Route('GET', '/email/verify/{id}/{hash}', []);
            $route->bind($request);
            $route->setParameter('id', $user->id);
            $route->setParameter('hash', sha1($user->getEmailForVerification()));

            return $route;
        });

        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->verify($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            [ 'message' => 'Email has been successfully verified' ],
            $response->getData(true)
        );
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_route_hash_is_not_the_same_as_the_user_email()
    {
        $this->expectException(AuthorizationException::class);

        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        // Set up the route so that we can get the proper parameters from the request.
        $request = (new Request())->create('/', 'GET');
        $request->setRouteResolver(function () use ($request, $user) {
            $route = new Route('GET', '/email/verify/{id}/{hash}', []);
            $route->bind($request);
            $route->setParameter('id', $user->id);
            $route->setParameter('hash', sha1('artorias.abysswalker@oolacile.com')); // Force a different email.

            return $route;
        });

        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->verify($request);
    }

    /** @test */
    public function it_will_only_verify_an_email_once()
    {
        $user = factory(User::class)->create(); // Email is already marked as verified.

        // Set up the route so that we can get the proper parameters from the request.
        $request = (new Request())->create('/', 'GET');
        $request->setRouteResolver(function () use ($request, $user) {
            $route = new Route('GET', '/email/verify/{id}/{hash}', []);
            $route->bind($request);
            $route->setParameter('id', $user->id);
            $route->setParameter('hash', sha1($user->getEmailForVerification()));

            return $route;
        });

        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->verify($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            [ 'message' => 'Email has already been verified' ],
            $response->getData(true)
        );
    }

    /** @test */
    public function it_will_emit_the_verified_event()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);
        
        Event::fake();

        // Set up the route so that we can get the proper parameters from the request.
        $request = (new Request())->create('/', 'GET');
        $request->setRouteResolver(function () use ($request, $user) {
            $route = new Route('GET', '/email/verify/{id}/{hash}', []);
            $route->bind($request);
            $route->setParameter('id', $user->id);
            $route->setParameter('hash', sha1($user->getEmailForVerification()));

            return $route;
        });

        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->verify($request);

        Event::assertDispatched(Verified::class);
    }

    /** @test */
    public function it_can_resend_a_verification_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
        ]);

        /** @var \Mockery\MockInterface|\Illuminate\Http\Request */
        $req = $this->mock(Request::class, function ($mock) use ($user) {
            $mock->shouldReceive('user')->andReturn($user);
        });
        
        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->resend($req);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'message' => 'Success',
        ], $response->getData(true));
    }

    /** @test */
    public function it_will_not_resend_a_verification_to_a_user_that_is_already_verified()
    {
        $user = factory(User::class)->create();

        /** @var \Mockery\MockInterface|\Illuminate\Http\Request */
        $req = $this->mock(Request::class, function ($mock) use ($user) {
            $mock->shouldReceive('user')->andReturn($user);
        });
        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->resend($req);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals([
            'error' => 'Email has already been verified',
        ], $response->getData(true));
    }

    /** @test */
    public function it_requires_an_authenticated_user_to_resend_a_verification_email()
    {
        $this->expectException(AuthorizationException::class);

        $controller = new VerificationController($this->app->make(UserService::class));
        $response = $controller->resend(new Request());
    }
}
