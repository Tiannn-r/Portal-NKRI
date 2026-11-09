<?php

namespace Tests\Feature;

use App\Models\AppCategory;
use App\Models\Application;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppLauncherTest extends TestCase
{
    use RefreshDatabase;

    protected AppCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $this->category = AppCategory::create([
            'name' => 'Operasional',
            'slug' => 'operasional',
            'sort_order' => 1,
        ]);
    }

    public function test_guest_is_redirected_to_login_when_opening_app(): void
    {
        $response = $this->get('/apps/odoo-erp/open');

        $response->assertRedirect('/login');
    }

    public function test_user_with_access_is_redirected_to_external_url_and_logged(): void
    {
        $dept = Department::create(['name' => 'Teknologi Informasi', 'code' => 'IT']);

        $user = User::factory()->create([
            'department_id' => $dept->id,
            'is_active' => true,
        ]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'IT Helpdesk',
            'slug' => 'it-helpdesk',
            'url' => 'https://helpdesk.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $app->departments()->attach($dept->id);

        $response = $this->actingAs($user)->get("/apps/{$app->slug}/open");

        // 1. Memverifikasi redirect 302 ke URL eksternal sistem
        $response->assertRedirect('https://helpdesk.nkri.co.id');

        // 2. Memverifikasi pencatatan log pada tabel app_launch_logs
        $this->assertDatabaseHas('app_launch_logs', [
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);
    }

    public function test_unauthorized_user_receives_403_forbidden(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Payroll Internal',
            'slug' => 'sistem-payroll-internal',
            'url' => 'https://payroll.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get("/apps/{$app->slug}/open");

        $response->assertStatus(403);

        // Pastikan tidak ada log peluncuran yang dicatat
        $this->assertDatabaseMissing('app_launch_logs', [
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);
    }

    public function test_user_with_deny_override_receives_403_even_with_department_access(): void
    {
        $dept = Department::create(['name' => 'Divisi Finansial', 'code' => 'FIN']);

        $user = User::factory()->create([
            'department_id' => $dept->id,
            'is_active' => true,
        ]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Pembelian Aset',
            'slug' => 'sistem-pembelian-aset',
            'url' => 'https://procurement.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        // Hubungkan departemen
        $app->departments()->attach($dept->id);

        // Pasang deny override khusus user
        $app->userExceptions()->attach($user->id, ['type' => 'deny']);

        $response = $this->actingAs($user)->get("/apps/{$app->slug}/open");

        // Wajib ditolak 403 (Deny Wins)
        $response->assertStatus(403);
    }

    public function test_maintenance_system_redirects_back_with_notification(): void
    {
        $dept = Department::create(['name' => 'Divisi Umum', 'code' => 'UMUM']);

        $user = User::factory()->create([
            'department_id' => $dept->id,
            'is_active' => true,
        ]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Aplikasi Presensi GPS',
            'slug' => 'aplikasi-presensi-gps',
            'url' => 'https://presensi.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'maintenance',
        ]);

        $app->departments()->attach($dept->id);

        $response = $this->actingAs($user)
            ->from('/dashboard')
            ->get("/apps/{$app->slug}/open");

        // Tidak boleh redirect ke URL eksternal, melainkan kembali ke halaman dashboard dengan pesan error
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');

        // Pastikan tidak dicatat ke log peluncuran berhasil
        $this->assertDatabaseMissing('app_launch_logs', [
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);
    }
}
