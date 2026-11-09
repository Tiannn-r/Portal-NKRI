<?php

namespace Tests\Unit;

use App\Models\AppCategory;
use App\Models\Application;
use App\Models\Department;
use App\Models\User;
use App\Services\AccessResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AccessResolverTest extends TestCase
{
    use RefreshDatabase;

    protected AccessResolver $resolver;
    protected AppCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new AccessResolver();

        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);

        $this->category = AppCategory::create([
            'name' => 'Kategori Uji',
            'slug' => 'kategori-uji',
            'sort_order' => 1,
        ]);
    }

    public function test_super_admin_can_access_any_system(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Admin');

        $app = Application::create([
            'name' => 'Sistem Rahasia',
            'slug' => 'sistem-rahasia',
            'url' => 'https://secret.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $this->assertTrue($this->resolver->canAccess($superAdmin, $app));
    }

    public function test_user_with_matching_role_can_access_system(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin Sistem');

        $app = Application::create([
            'name' => 'Sistem Infrastruktur',
            'slug' => 'sistem-infrastruktur',
            'url' => 'https://infra.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $adminRole = Role::where('name', 'Admin Sistem')->first();
        $app->roles()->attach($adminRole->id);

        $this->assertTrue($this->resolver->canAccess($user, $app));
    }

    public function test_user_with_matching_department_can_access_system(): void
    {
        $dept = Department::create(['name' => 'Divisi Keuangan', 'code' => 'FIN']);

        $user = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Pembayaran',
            'slug' => 'sistem-pembayaran',
            'url' => 'https://pay.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $app->departments()->attach($dept->id);

        $this->assertTrue($this->resolver->canAccess($user, $app));
    }

    public function test_user_with_allow_exception_can_access_without_role_or_department(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Khusus',
            'slug' => 'sistem-khusus',
            'url' => 'https://special.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        // Berikan izin perorangan allow
        $app->userExceptions()->attach($user->id, ['type' => 'allow', 'notes' => 'Izin perorangan']);

        $this->assertTrue($this->resolver->canAccess($user, $app));
    }

    public function test_deny_exception_always_overrides_role_and_department_permissions(): void
    {
        $dept = Department::create(['name' => 'Divisi Pengadaan', 'code' => 'PROC']);
        $role = Role::where('name', 'User')->first();

        $user = User::factory()->create([
            'department_id' => $dept->id,
        ]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Lelang',
            'slug' => 'sistem-lelang',
            'url' => 'https://lelang.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        // Hubungkan role DAN departemen ke aplikasi (dua-duanya cocok)
        $app->roles()->attach($role->id);
        $app->departments()->attach($dept->id);

        // Pasang pengecualian DENY khusus untuk user ini
        $app->userExceptions()->attach($user->id, ['type' => 'deny', 'notes' => 'Conflict of Interest']);

        // DENY WINS: Harus menghasilkan FALSE
        $this->assertFalse($this->resolver->canAccess($user, $app));
    }

    public function test_user_without_any_matching_permission_cannot_access(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Terbatas',
            'slug' => 'sistem-terbatas',
            'url' => 'https://restricted.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        $this->assertFalse($this->resolver->canAccess($user, $app));
    }

    public function test_inactive_system_is_never_accessible_to_regular_user(): void
    {
        $dept = Department::create(['name' => 'Divisi SDM', 'code' => 'HR']);

        $user = User::factory()->create(['department_id' => $dept->id]);
        $user->assignRole('User');

        $app = Application::create([
            'name' => 'Sistem Tua',
            'slug' => 'sistem-tua',
            'url' => 'https://old.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'inactive',
        ]);

        $app->departments()->attach($dept->id);

        $this->assertFalse($this->resolver->canAccess($user, $app));
    }
}
