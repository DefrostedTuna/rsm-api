<?php

namespace Tests\Feature\Routes;

use App\Contracts\Services\AuthService;
use App\Models\Location;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticates a user and returns the associated JWT.
     *
     * @param  \App\Models\User  $user
     * @param  string            $passwordOverride
     *
     * @return string
     */
    public function getJWT(User $user, string $passwordOverride = null): string
    {
        $authService = $this->app->make(AuthService::class);
        $token = $authService->attemptLoginWithCredentials([
            'email' => $user->email,
            'password' => $passwordOverride ?: 'password',
        ]);

        return $token['access_token'];
    }

    /** @test */
    public function it_can_store_a_rating_for_a_given_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $token = $this->getJWT($user);
        
        $response = $this->post("/api/locations/{$location->id}/ratings/", [
            'rating' => 3,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'location_id',
            'rating',
        ], $response->json()['data']);
        $response->assertJsonFragment([
            'success' => true,
            'message' => 'Rating successful.',
        ]);
        $response->assertJsonFragment([
            'user_id' => $user->id,
            'location_id' => $location->id,
            'rating' => 3,
        ], $response->json()['data']);
    }

    /** @test */
    public function it_can_update_an_existing_rating_for_a_given_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();
        $rating = factory(Rating::class)->create([
            'user_id' => $user->id,
            'location_id' => $location->id,
            'rating' => 1,
        ]);

        $token = $this->getJWT($user);
        
        $response = $this->post("/api/locations/{$location->id}/ratings/", [
            'rating' => 3,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'location_id',
            'rating',
        ], $response->json()['data']);
        $response->assertJsonFragment([
            'success' => true,
            'message' => 'Rating successful.',
        ]);
        $response->assertJsonFragment([
            'id' => $rating->id,
            'user_id' => $user->id,
            'location_id' => $location->id,
            'rating' => 3,
        ]);
    }

    /** @test */
    public function only_an_authenticated_user_can_leave_a_rating()
    {
        $location = factory(Location::class)->create();
        
        $response = $this->post("/api/locations/{$location->id}/ratings/", [
            'rating' => 3,
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'Token not provided',
        ]);
    }

    /** @test */
    public function it_will_return_an_error_if_the_location_does_not_exist()
    {
        $user = factory(User::class)->create();

        $token = $this->getJWT($user);
        
        $response = $this->post("/api/locations/some-invalid-id/ratings/", [
            'rating' => 3,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(500);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'There was a problem submitting the rating.',
        ]);
    }

    /** @test */
    public function it_validates_the_rating_field()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $token = $this->getJWT($user);
        
        $response = $this->post(
            "/api/locations/{$location->id}/ratings/",
            [],
            ['Authorization' => "Bearer {$token}",]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors',
        ]);
        $response->assertJson([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => [
                'rating' => [
                    'The rating field is required.',
                ],
            ],
        ]);

        $response = $this->post(
            "/api/locations/{$location->id}/ratings/",
            ['rating' => 'string'],
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertJson([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => [
                'rating' => [
                    'The rating must be a number.',
                    'The rating must be 1 digits.',
                    'The rating must be between 1 and 5.',
                ],
            ],
        ]);

        $response = $this->post(
            "/api/locations/{$location->id}/ratings/",
            ['rating' => 1.5],
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertJson([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => [
                'rating' => [
                    'The rating must be 1 digits.',
                ],
            ],
        ]);

        $response = $this->post(
            "/api/locations/{$location->id}/ratings/",
            ['rating' => 6],
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertJson([
            'success' => false,
            'message' => 'The given data was invalid.',
            'errors' => [
                'rating' => [
                    'The rating must be between 1 and 5.',
                ],
            ],
        ]);
    }
}
