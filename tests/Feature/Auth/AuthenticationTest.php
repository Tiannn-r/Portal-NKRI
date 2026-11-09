<?php

namespace Tests\Feature\Auth;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
        $this->seed(\Database\Seeders\DepartmentSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'karyawan@nkri.co.id',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'karyawan@nkri.co.id',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'karyawan@nkri.co.id',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        $this->post('/login', [
            'email' => 'karyawan@nkri.co.id',
            'password' => 'WrongPassword',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_users_can_not_authenticate(): void
    {
        $user = User::factory()->create([
            'email' => 'nonaktif@nkri.co.id',
            'password' => bcrypt('Password123!',),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'nonaktif@nkri.co.id',
            'password' => 'Password123!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}

