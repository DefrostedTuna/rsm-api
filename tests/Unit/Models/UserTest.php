<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserTest extends TestCase
{
    /** @test */
    public function it_uses_the_proper_database_table()
    {
        $user = new User();

        $this->assertEquals('users', $user->getTable());
    }

    /** @test */
    public function it_sets_the_primary_key_type_to_string()
    {
        $user = new User();

        $this->assertEquals('string', $user->getKeyType());
    }

    /** @test */
    public function it_explicitly_defines_the_properties_to_be_assigned_in_mass()
    {
        $user = new User();

        $expected = [
            'username',
            'email',
            'password',
        ];

        $this->assertEquals($expected, $user->getFillable());
    }

    /** @test */
    public function it_explicitly_defines_the_visible_fields_for_api_consumption()
    {
        $user = new User();

        $visibleFields = [
            'username',
            'email',
        ];

        $this->assertEquals($visibleFields, $user->getVisible());
    }

    /** @test */
    public function it_explicitly_defines_the_hidden_fields_for_api_consumption()
    {
        $user = new User();

        $hiddenFields = [
            'password', 
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $this->assertEquals($hiddenFields, $user->getHidden());
    }

    /** @test */
    public function it_explicitly_defines_the_return_type_for_each_field() 
    {
        $user = new User();

        $fields = $user->getCasts();

        $expected = [
            'username'          => 'string',
            'email'             => 'string',
            'password'          => 'string',
            'remember_token'    => 'string',
            'email_verified_at' => 'datetime',
        ];

        $this->assertEquals($expected, $fields);
    }

    /** @test */
    public function it_implements_the_jwt_subject_contract()
    {
        $user = new User();

        $this->assertInstanceOf(JWTSubject::class, $user);
    }
}
