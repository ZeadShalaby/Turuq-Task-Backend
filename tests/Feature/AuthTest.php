<?php

namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // ? register test
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'age' => 20
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    // ? login test
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user'
                ]
            ]);
    }

    // ? me test
    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();


        $token = auth()->login($user);


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson('/api/me');


        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);
    }

    // ? refresh test
    public function test_user_can_refresh_token()
    {
        $user = User::factory()->create();

        $token = auth()->login($user);


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->postJson('/api/refresh');


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user'
                ]
            ]);
    }

    // ? logout test
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $token = auth()->login($user);


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->postJson('/api/logout');


        $response->assertStatus(200);
    }

}