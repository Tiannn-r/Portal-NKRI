<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AccessMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apps = Application::pluck('id', 'slug');
        $depts = Department::pluck('id', 'code');
        $roles = Role::pluck('id', 'name');

        // 1. Hak Akses Berdasarkan Role
        // Helpdesk & Presensi dapat diakses semua role karyawan
        if (isset($apps['helpdesk-it-support-ticket']) && isset($roles['User'])) {
            DB::table('application_role')->updateOrInsert(
                ['application_id' => $apps['helpdesk-it-support-ticket'], 'role_id' => $roles['User']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        if (isset($apps['business-intelligence-reporting']) && isset($roles['Admin Sistem'])) {
            DB::table('application_role')->updateOrInsert(
                ['application_id' => $apps['business-intelligence-reporting'], 'role_id' => $roles['Admin Sistem']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Hak Akses Berdasarkan Departemen
        // HRIS -> HR & IT
        $this->attachDept($apps['hris-portal-kepegawaian'] ?? null, $depts['HR'] ?? null);
        $this->attachDept($apps['hris-portal-kepegawaian'] ?? null, $depts['IT'] ?? null);

        // ERP Supply Chain -> OPS & FIN
        $this->attachDept($apps['erp-supply-chain-inventory'] ?? null, $depts['OPS'] ?? null);
        $this->attachDept($apps['erp-supply-chain-inventory'] ?? null, $depts['FIN'] ?? null);

        // Akuntansi -> FIN
        $this->attachDept($apps['sistem-akuntansi-faktur'] ?? null, $depts['FIN'] ?? null);

        // CRM -> MKT
        $this->attachDept($apps['crm-manajemen-pelanggan'] ?? null, $depts['MKT'] ?? null);

        // E-Procurement -> OPS & FIN
        $this->attachDept($apps['e-procurement-pengadaan-barang'] ?? null, $depts['OPS'] ?? null);
        $this->attachDept($apps['e-procurement-pengadaan-barang'] ?? null, $depts['FIN'] ?? null);

        // Legal -> LGL & IT
        $this->attachDept($apps['portal-dokumen-kontrak-legal'] ?? null, $depts['LGL'] ?? null);
        $this->attachDept($apps['portal-dokumen-kontrak-legal'] ?? null, $depts['IT'] ?? null);

        // 3. Pengecualian Akses Perorangan (application_user)
        // Kasus A: ALLOW - Dewi (OPS) diizinkan mengakses Akuntansi untuk cross-check inventaris
        $dewi = User::where('email', 'dewi.ops@nkri.co.id')->first();
        if ($dewi && isset($apps['sistem-akuntansi-faktur'])) {
            DB::table('application_user')->updateOrInsert(
                ['application_id' => $apps['sistem-akuntansi-faktur'], 'user_id' => $dewi->id],
                [
                    'type' => 'allow',
                    'notes' => 'Izin khusus verifikasi persediaan fisik gudang',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Kasus B: DENY (Deny Wins) - Andi (FIN) dilarang mengakses E-Procurement untuk cegah benturan kepentingan
        $andi = User::where('email', 'andi.fin@nkri.co.id')->first();
        if ($andi && isset($apps['e-procurement-pengadaan-barang'])) {
            DB::table('application_user')->updateOrInsert(
                ['application_id' => $apps['e-procurement-pengadaan-barang'], 'user_id' => $andi->id],
                [
                    'type' => 'deny',
                    'notes' => 'Pengecualian segregasi tugas (SOD) audit internal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function attachDept(?int $appId, ?int $deptId): void
    {
        if ($appId && $deptId) {
            DB::table('application_department')->updateOrInsert(
                ['application_id' => $appId, 'department_id' => $deptId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
