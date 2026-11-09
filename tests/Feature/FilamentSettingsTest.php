<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_super_admin_can_access_settings_page(): void
    {
        $admin = User::where('email', 'admin@nkri.co.id')->first();

        $this->actingAs($admin)
            ->get('/admin/manage-settings')
            ->assertSuccessful();
    }

    public function test_super_admin_can_update_portal_settings(): void
    {
        $admin = User::where('email', 'admin@nkri.co.id')->first();

        Livewire::actingAs($admin)
            ->test(ManageSettings::class)
            ->fillForm([
                'hero_title' => 'Portal Terpadu PT NKRI V2',
                'hero_description' => 'Akses seluruh aplikasi perusahaan dalam satu pintu.',
                'footer_text' => 'PT NKRI 2026. Hak Cipta Dilindungi.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals('Portal Terpadu PT NKRI V2', Setting::get('hero_title'));
        $this->assertEquals('Akses seluruh aplikasi perusahaan dalam satu pintu.', Setting::get('hero_description'));
        $this->assertEquals('PT NKRI 2026. Hak Cipta Dilindungi.', Setting::get('footer_text'));

        // Pastikan perubahan langsung tampak di landing page publik
        $this->get('/')
            ->assertSuccessful()
            ->assertInertia(fn ($page) => 
                $page->where('settings.hero_title', 'Portal Terpadu PT NKRI V2')
                    ->where('settings.hero_description', 'Akses seluruh aplikasi perusahaan dalam satu pintu.')
                    ->where('settings.footer_text', 'PT NKRI 2026. Hak Cipta Dilindungi.')
            );
    }
}

