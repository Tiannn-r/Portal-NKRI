<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceQueryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Pastikan halaman portal bebas dari masalah query N+1.
     * Jumlah query harus konstan dan efisien meskipun jumlah aplikasi bertambah.
     */
    public function test_portal_home_page_is_free_from_n_plus_one_queries(): void
    {
        $user = User::where('email', 'admin@nkri.co.id')->first();

        // Aktifkan pencatatan query
        DB::enableQueryLog();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Debug: print queries
        // dump(array_column($queries, 'query'));

        // Query count harus tetap konstan (13 query) dan tidak bertambah seiring jumlah aplikasi
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Terdeteksi potensi N+1 query! Total query yang dijalankan: {$queryCount}"
        );

        // Pastikan tidak ada query berulang ke tabel yang sama untuk relasi aplikasi
        $tableQueries = array_map(fn ($q) => $q['query'], $queries);
        $appRoleQueries = array_filter($tableQueries, fn ($sql) => str_contains($sql, 'application_role'));
        $appDeptQueries = array_filter($tableQueries, fn ($sql) => str_contains($sql, 'application_department'));
        $appUserQueries = array_filter($tableQueries, fn ($sql) => str_contains($sql, 'application_user'));

        // Masing-masing relasi hanya boleh di-query 1 kali (eager loaded via whereIn)
        $this->assertLessThanOrEqual(1, count($appRoleQueries), 'Terjadi N+1 query pada relasi roles aplikasi');
        $this->assertLessThanOrEqual(1, count($appDeptQueries), 'Terjadi N+1 query pada relasi departments aplikasi');
        $this->assertLessThanOrEqual(1, count($appUserQueries), 'Terjadi N+1 query pada relasi userExceptions aplikasi');
    }

    /**
     * Verifikasi performa ketika jumlah aplikasi digandakan.
     */
    public function test_query_count_remains_constant_when_applications_increase(): void
    {
        $user = User::where('email', 'andi.fin@nkri.co.id')->first();

        // Gandakan aplikasi sebanyak 10 aplikasi tambahan
        $existingApp = Application::first();
        for ($i = 1; $i <= 10; $i++) {
            Application::create([
                'name' => "Sistem Tambahan {$i}",
                'slug' => "sistem-tambahan-{$i}",
                'description' => "Deskripsi pengujian performa {$i}",
                'url' => "https://app{$i}.nkri.co.id",
                'app_category_id' => $existingApp->app_category_id,
                'status' => 'active',
                'sort_order' => 100 + $i,
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        // Tetap harus konstan (13 query) meskipun jumlah aplikasi bertambah banyak
        $this->assertLessThanOrEqual(
            15,
            $queryCount,
            "Penambahan aplikasi menyebabkan peningkatan jumlah query linear! Query count: {$queryCount}"
        );
        $this->assertEquals(13, $queryCount);
    }
}
