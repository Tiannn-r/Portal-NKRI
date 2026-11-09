<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_regular_user_cannot_access_filament_admin_panel(): void
    {
        $user = User::where('email', 'andi.fin@nkri.co.id')->first();

        $response = $this->actingAs($user, 'admin')->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_sistem_and_admin_konten_cannot_access_panel_only_super_admin_allowed(): void
    {
        $adminSistem = User::where('email', 'budi.it@nkri.co.id')->first();
        $adminKonten = User::where('email', 'siti.hr@nkri.co.id')->first();

        // Hanya Super Admin yang diizinkan masuk CMS Filament
        $this->actingAs($adminSistem, 'admin')->get('/admin')->assertStatus(403);
        $this->actingAs($adminKonten, 'admin')->get('/admin')->assertStatus(403);
    }

    public function test_super_admin_can_access_filament_admin_panel(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($superAdmin, 'admin')->get('/admin');

        $response->assertStatus(200);
    }

    public function test_super_admin_can_access_manage_user_access_page(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($superAdmin, 'admin')->get('/admin/manage-user-access');

        $response->assertStatus(200);
    }
}
