<?php

use App\Contracts\Services\AuthService;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_authenticate_an_existing_user()
    {
        $user = factory(User::class)->create();

        $authService = $this->app->make(AuthService::class);
        $loginController = new LoginController($authService);

        $response = $loginController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]));

        $this->assertAuthenticated();
        
        // Super gross looking assertions...
        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Successfully authenticated.',
        ]);
        $this->assertArrayHasKey('data', $response->getData(true));

        $responseData = $response->getData(true)['data'];
        $this->assertArrayHasKey('access_token', $responseData);
        $this->assertArrayHasKey('token_type', $responseData);
        $this->assertArrayHasKey('access_token', $responseData);
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_credentials_are_incorrect()
    {
        $user = factory(User::class)->create();

        $authService = $this->app->make(AuthService::class);
        $loginController = new LoginController($authService);

        $response = $loginController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]));
        
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertContains($response->getData(true), [
            'success' => true,
            'message' => 'Unauthorized.',
        ]);
    }

    /** @test */
    public function it_can_destroy_a_user_token()
    {
        $user = factory(User::class)->create();

        $authService = $this->app->make(AuthService::class);
        $loginController = new LoginController($authService);

        $response = $loginController->login(new Request([
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]));

        // User is authenticated.
        $this->assertAuthenticated();

        // Invalidate the user.
        $loginController->logout();

        // User should no longer be authenticated.
        $this->isFalse($this->isAuthenticated());
    }
}