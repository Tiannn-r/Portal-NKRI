<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\AppCategory;
use App\Models\Application;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_sees_accurate_accessible_count(): void
    {
        $user = User::where('email', 'admin@nkri.co.id')->first();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Welcome')
                ->where('auth.user.id', $user->id)
                ->has('stats.accessible_count')
                ->has('stats.total_count')
        );
    }

    public function test_user_can_toggle_application_favorite(): void
    {
        $user = User::where('email', 'andi.fin@nkri.co.id')->first();
        $app = Application::first();

        $response = $this->actingAs($user)->postJson("/apps/{$app->id}/favorite");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_favorite' => true,
                'application_id' => $app->id,
            ]);

        $this->assertDatabaseHas('user_favorites', [
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);

        // Toggle second time should remove it
        $response2 = $this->actingAs($user)->postJson("/apps/{$app->id}/favorite");
        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_favorite' => false,
            ]);

        $this->assertDatabaseMissing('user_favorites', [
            'user_id' => $user->id,
            'application_id' => $app->id,
        ]);
    }

    public function test_guest_cannot_toggle_favorite(): void
    {
        $app = Application::first();

        $response = $this->postJson("/apps/{$app->id}/favorite");
        $response->assertStatus(401);
    }

    public function test_category_with_applications_can_be_deleted_smoothly(): void
    {
        $category = AppCategory::has('applications')->first();
        $this->assertNotNull($category);

        $appIds = $category->applications()->pluck('id')->toArray();
        $this->assertNotEmpty($appIds);

        // Hapus kategori
        $category->delete();

        $this->assertDatabaseMissing('app_categories', ['id' => $category->id]);

        // Aplikasi tetap ada dengan app_category_id menjadi null
        foreach ($appIds as $appId) {
            $app = Application::find($appId);
            $this->assertNotNull($app);
            $this->assertNull($app->app_category_id);
        }
    }
}

