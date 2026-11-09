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

    public function test_regular_user_cannot_access_filament_admin_panel(): void
    {
        $user = User::where('email', 'andi.fin@nkri.co.id')->first();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_filament_admin_panel(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($superAdmin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_admin_sistem_can_access_application_management(): void
    {
        $adminSistem = User::where('email', 'budi.it@nkri.co.id')->first();

        $response = $this->actingAs($adminSistem)->get('/admin/applications');

        $response->assertStatus(200);
    }

    public function test_admin_konten_can_access_announcements_but_forbidden_from_applications(): void
    {
        $adminKonten = User::where('email', 'siti.hr@nkri.co.id')->first();

        // Bisa akses pengumuman
        $responseAnnouncements = $this->actingAs($adminKonten)->get('/admin/announcements');
        $responseAnnouncements->assertStatus(200);

        // Dilarang akses aplikasi
        $responseApplications = $this->actingAs($adminKonten)->get('/admin/applications');
        $responseApplications->assertStatus(403);
    }
}

