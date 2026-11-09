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

    public function test_guest_can_render_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Welcome')
                ->has('settings')
                ->has('systems')
                ->has('categories')
                ->where('settings.hero_title', 'Selamat datang di Portal NKRI')
                ->where('auth.user', null)
        );
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
}
