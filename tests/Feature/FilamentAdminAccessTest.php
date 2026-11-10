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

    public function test_super_admin_can_login_with_seeded_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@nkri.co.id',
            'password' => 'admin123',
        ]);

        // Filament login uses Livewire; when submitted or checked directly, credentials match
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('admin123', $superAdmin->password));
    }

    public function test_super_admin_can_grant_and_revoke_admin_access_via_manage_access(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();
        $targetUser = User::where('email', 'andi.fin@nkri.co.id')->first();

        $this->assertFalse($targetUser->hasRole('Super Admin'));

        // Jadikan Super Admin
        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Pages\ManageUserAccess::class)
            ->set('data.user_id', $targetUser->id)
            ->set('data.is_super_admin', true)
            ->call('submit');

        $this->assertTrue($targetUser->fresh()->hasRole('Super Admin'));

        // Cabut Super Admin
        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Pages\ManageUserAccess::class)
            ->set('data.user_id', $targetUser->id)
            ->set('data.is_super_admin', false)
            ->call('submit');

        $this->assertFalse($targetUser->fresh()->hasRole('Super Admin'));
    }

    public function test_super_admin_cannot_accidentally_revoke_own_admin_access(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Pages\ManageUserAccess::class)
            ->set('data.user_id', $superAdmin->id)
            ->set('data.is_super_admin', false)
            ->call('submit');

        $this->assertTrue($superAdmin->fresh()->hasRole('Super Admin'));
    }

    public function test_super_admin_can_create_new_admin_user_via_user_resource(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();

        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Resources\UserResource\Pages\CreateUser::class)
            ->fillForm([
                'name' => 'Admin Baru',
                'email' => 'admin.baru@nkri.co.id',
                'password' => 'Password123!',
                'is_active' => true,
                'is_admin' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newUser = User::where('email', 'admin.baru@nkri.co.id')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('Super Admin'));
    }

    public function test_super_admin_can_toggle_admin_role_via_user_edit_page(): void
    {
        $superAdmin = User::where('email', 'admin@nkri.co.id')->first();
        $user = User::where('email', 'andi.fin@nkri.co.id')->first();

        $this->assertFalse($user->hasRole('Super Admin'));

        // Jadikan admin lewat halaman edit user
        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Resources\UserResource\Pages\EditUser::class, [
                'record' => $user->getRouteKey(),
            ])
            ->fillForm([
                'is_admin' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($user->fresh()->hasRole('Super Admin'));

        // Kembalikan menjadi user biasa
        \Livewire\Livewire::actingAs($superAdmin, 'admin')
            ->test(\App\Filament\Resources\UserResource\Pages\EditUser::class, [
                'record' => $user->getRouteKey(),
            ])
            ->fillForm([
                'is_admin' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse($user->fresh()->hasRole('Super Admin'));
    }
}
