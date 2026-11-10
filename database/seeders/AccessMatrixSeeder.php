<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apps = Application::pluck('id', 'slug');

        // Budi (IT): Helpdesk, HRIS, ERP, BI, Legal
        $budi = User::where('email', 'budi.it@nkri.co.id')->first();
        if ($budi) {
            $this->grantAccess($budi->id, [
                $apps['helpdesk-it-support-ticket'] ?? null,
                $apps['hris-portal-kepegawaian'] ?? null,
                $apps['erp-supply-chain-inventory'] ?? null,
                $apps['business-intelligence-reporting'] ?? null,
                $apps['portal-dokumen-kontrak-legal'] ?? null,
            ]);
        }

        // Siti (HR): HRIS, Helpdesk
        $siti = User::where('email', 'siti.hr@nkri.co.id')->first();
        if ($siti) {
            $this->grantAccess($siti->id, [
                $apps['hris-portal-kepegawaian'] ?? null,
                $apps['helpdesk-it-support-ticket'] ?? null,
            ]);
        }

        // Andi (FIN): Akuntansi, ERP, Helpdesk
        $andi = User::where('email', 'andi.fin@nkri.co.id')->first();
        if ($andi) {
            $this->grantAccess($andi->id, [
                $apps['sistem-akuntansi-faktur'] ?? null,
                $apps['erp-supply-chain-inventory'] ?? null,
                $apps['helpdesk-it-support-ticket'] ?? null,
            ]);
        }

        // Dewi (OPS): ERP, E-Procurement, Akuntansi, Helpdesk
        $dewi = User::where('email', 'dewi.ops@nkri.co.id')->first();
        if ($dewi) {
            $this->grantAccess($dewi->id, [
                $apps['erp-supply-chain-inventory'] ?? null,
                $apps['e-procurement-pengadaan-barang'] ?? null,
                $apps['sistem-akuntansi-faktur'] ?? null,
                $apps['helpdesk-it-support-ticket'] ?? null,
            ]);
        }

        // Eko (MKT): CRM, Helpdesk
        $eko = User::where('email', 'eko.mkt@nkri.co.id')->first();
        if ($eko) {
            $this->grantAccess($eko->id, [
                $apps['crm-manajemen-pelanggan'] ?? null,
                $apps['helpdesk-it-support-ticket'] ?? null,
            ]);
        }
    }

    private function grantAccess(int $userId, array $appIds): void
    {
        foreach (array_filter($appIds) as $appId) {
            DB::table('application_user')->updateOrInsert(
                ['application_id' => $appId, 'user_id' => $userId],
                [
                    'type' => 'allow',
                    'notes' => 'Hak akses langsung pengguna',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
