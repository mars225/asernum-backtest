<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'mobile' => '1234567890',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'access_token', 'token_type']);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'role']);
    }

    /** @test */
    public function authenticated_user_can_view_profile()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertOk()
            ->assertJson(['id' => $user->id, 'email' => $user->email]);
    }
}
