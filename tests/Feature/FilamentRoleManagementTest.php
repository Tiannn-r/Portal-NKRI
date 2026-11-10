<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_access_roles_index_page(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($superAdmin, 'admin')->get('/admin/roles');

        $response->assertStatus(200);
        $response->assertSee('Role Pengguna');
    }

    public function test_super_admin_can_access_create_role_page(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($superAdmin, 'admin')->get('/admin/roles/create');

        $response->assertStatus(200);
    }

    public function test_super_admin_can_create_a_new_role(): void
    {
        $newRole = Role::create([
            'name' => 'Operator Manufaktur Rahasia',
            'guard_name' => 'web',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Operator Manufaktur Rahasia',
            'guard_name' => 'web',
        ]);
    }

    public function test_super_admin_can_delete_a_custom_role(): void
    {
        $customRole = Role::create([
            'name' => 'Temporary Contractor',
            'guard_name' => 'web',
        ]);

        $customRole->delete();

        $this->assertDatabaseMissing('roles', [
            'name' => 'Temporary Contractor',
        ]);
    }

    public function test_super_admin_role_is_protected_by_policy(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        $this->assertFalse($superAdmin->can('delete', $superAdminRole));
        $this->assertFalse($superAdmin->can('update', $superAdminRole));
    }

    public function test_regular_user_cannot_access_roles_page(): void
    {
        $regularUser = User::where('email', 'andi.fin@nkri.co.id')->first();

        $response = $this->actingAs($regularUser, 'admin')->get('/admin/roles');

        $response->assertStatus(403);
    }
}

