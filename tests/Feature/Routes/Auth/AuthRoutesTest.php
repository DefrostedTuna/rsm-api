<?php

namespace Tests\Feature\Routes\Auth;

use App\Contracts\Services\UserService;
use App\Events\Auth\Registered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_user()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'confirm_password' => 'GreatGreyWolfSif',
        ];

        $response = $this->post('/api/register', $userInfo);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'username' => $userInfo['username'],
            'email' => $userInfo['email'],
        ]);
    }

    /** @test */
    public function registering_a_new_user_should_return_a_token()
    {
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'confirm_password' => 'GreatGreyWolfSif',
        ];

        $response = $this->post('/api/register', $userInfo);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);
    }

    /** @test */
    public function it_fires_the_registered_event_when_a_user_is_created()
    {
        // We only want to fake the Registered event.
        // Faking all events will prevent UUID generation.
        Event::fake([
            Registered::class,
        ]);

        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'confirm_password' => 'GreatGreyWolfSif',
        ];

        $response = $this->post('/api/register', $userInfo);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);
        Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function it_throws_an_exception_when_it_can_not_create_a_user()
    {
        $this->instance(
            UserService::class,
            $this->mock(UserService::class, function ($mock) {
                $mock->shouldReceive('create')->once()->andThrow(new \Exception('You shall not pass!', 9001));
            })
        );
        
        $userInfo = [
            'username' => 'Artorias',
            'email' => 'artorias.abysswalker@oolacile.com',
            'password' => 'GreatGreyWolfSif',
            'confirm_password' => 'GreatGreyWolfSif',
        ];

        $response = $this->post('/api/register', $userInfo);

        $response->assertStatus(500);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'There was an error creating the account.',
        ]);
    }

    /** @test */
    public function it_can_authenticate_an_existing_user()
    {
        $user = factory(User::class)->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticated();
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_credentials_are_incorrect()
    {
        $user = factory(User::class)->create();

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized.',
        ]);
    }

    /** @test */
    public function it_can_destroy_a_user_token()
    {
        $user = factory(User::class)->create();

        $loginResponse = $this->post('/api/login', [
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $this->assertAuthenticated();

        // Invalidate the user.
        $logoutResponse = $this->get('/api/logout', [
            'Authorization' => "Bearer {$loginResponse->original['data']['access_token']}",
        ]);

        // User should no longer be authenticated.
        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJsonStructure([
            'success',
            'message',
        ]);
        $logoutResponse->assertJson([
            'success' => true,
            'message' => 'The user has successfully been logged out.',
        ]);
        $this->isFalse($this->isAuthenticated());
        // TODO: Check to make sure the token is blacklisted?
    }

    /** @test */
    public function it_throttles_login_attempts()
    {
        $user = factory(User::class)->create();

        $userInfo = [
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'incorrect-password',
        ];

        $responseOne    = $this->post('/api/login', $userInfo);
        $responseTwo    = $this->post('/api/login', $userInfo);
        $responseThree  = $this->post('/api/login', $userInfo);
        $responseFour   = $this->post('/api/login', $userInfo);
        $responseFive   = $this->post('/api/login', $userInfo);
        $responseSix    = $this->post('/api/login', $userInfo);

        $responseSix->assertStatus(400);
        $responseSix->assertJsonStructure([
            'success',
            'message',
        ]);
        $responseSix->assertJson([
            'success' => false,
            'message' => 'Too many authentication attempts.',
        ]);
        $this->isFalse($this->isAuthenticated());
    }

    /** @test */
    public function it_only_allows_authenticated_users_to_logout()
    {
        $logoutResponse = $this->getJson('/api/logout');

        $logoutResponse->assertStatus(401);
        $logoutResponse->assertJsonStructure([
            'success',
            'message',
        ]);
        $logoutResponse->assertJson([
            'success' => false,
            'message' => 'Token not provided',
        ]);
    }
}
