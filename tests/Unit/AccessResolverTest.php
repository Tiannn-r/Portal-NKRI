<?php

namespace Tests\Unit;

use App\Models\AppCategory;
use App\Models\Application;
use App\Models\User;
use App\Services\AccessResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_user_with_assigned_access_can_access_system(): void
    {
        $user = User::factory()->create();

        $app = Application::create([
            'name' => 'Sistem Manufaktur',
            'slug' => 'sistem-manufaktur',
            'url' => 'https://mfg.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'active',
        ]);

        // Hubungkan akses langsung user ke sistem
        $app->allowedUsers()->attach($user->id);

        $this->assertTrue($this->resolver->canAccess($user, $app));
    }

    public function test_user_without_assigned_access_cannot_access_system(): void
    {
        $user = User::factory()->create();

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
        $user = User::factory()->create();

        $app = Application::create([
            'name' => 'Sistem Nonaktif',
            'slug' => 'sistem-nonaktif',
            'url' => 'https://old.nkri.co.id',
            'app_category_id' => $this->category->id,
            'status' => 'inactive',
        ]);

        $app->allowedUsers()->attach($user->id);

        $this->assertFalse($this->resolver->canAccess($user, $app));
    }
}
