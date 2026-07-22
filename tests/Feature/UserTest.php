<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_get_users()
    {
        User::factory()->count(5)->create();


        $token = auth()->login(User::first());


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson('/api/users');


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'users',
                    'pagination'
                ]
            ]);
    }



    public function test_can_create_user()
    {
        $token = auth()->login(
            User::factory()->create()
        );


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->postJson('/api/users', [
                    'name' => 'New User',
                    'email' => 'new@test.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123'
                ]);


        $response->assertStatus(200);


        $this->assertDatabaseHas('users', [
            'email' => 'new@test.com'
        ]);
    }



    public function test_can_show_user()
    {
        $user = User::factory()->create();


        $token = auth()->login(
            User::factory()->create()
        );


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson('/api/users/' . $user->id);


        $response->assertStatus(200);
    }



    public function test_can_update_user()
    {
        $user = User::factory()->create();


        $token = auth()->login($user);


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->putJson('/api/users/' . $user->id, [
                    'name' => 'Updated Name'
                ]);


        $response->assertStatus(200);


        $this->assertDatabaseHas('users', [
            'name' => 'Updated Name'
        ]);
    }



    public function test_can_delete_user()
    {
        $user = User::factory()->create();


        $token = auth()->login($user);


        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->deleteJson('/api/users/' . $user->id);


        $response->assertStatus(200);


        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}