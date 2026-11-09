<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Teknologi Informasi',
                'code' => 'IT',
                'description' => 'Divisi pengembangan sistem, infrastruktur jaringan, dan dukungan teknis internal.',
            ],
            [
                'name' => 'Sumber Daya Manusia',
                'code' => 'HR',
                'description' => 'Divisi manajemen kepegawaian, kompensasi, dan pengembangan talenta.',
            ],
            [
                'name' => 'Keuangan & Akuntansi',
                'code' => 'FIN',
                'description' => 'Divisi pelaporan keuangan, perpajakan, anggaran, dan perbendaharaan.',
            ],
            [
                'name' => 'Operasional & Logistik',
                'code' => 'OPS',
                'description' => 'Divisi rantai pasok, manajemen pergudangan, dan operasional harian.',
            ],
            [
                'name' => 'Pemasaran & Penjualan',
                'code' => 'MKT',
                'description' => 'Divisi ekspansi pasar, akuisisi pelanggan, dan komunikasi korporat.',
            ],
            [
                'name' => 'Legal & Kepatuhan',
                'code' => 'LGL',
                'description' => 'Divisi hukum, audit kepatuhan regulasi, dan perjanjian kontrak.',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}

