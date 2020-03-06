<?php

namespace Tests\Feature\Routes\Auth;

use App\Contracts\Services\UserService;
use App\Events\Auth\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterRoutesTest extends TestCase
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
            $this->getResolvedClassName(UserService::class),
            $this->mock(
                $this->getResolvedClassName(UserService::class), 
                function ($mock) {
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
}