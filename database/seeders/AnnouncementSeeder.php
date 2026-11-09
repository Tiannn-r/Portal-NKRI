<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::where('email', 'admin@nkri.co.id')->first()
            ?? User::where('email', 'siti.hr@nkri.co.id')->first();

        $announcements = [
            [
                'title' => 'Pemeliharaan Rutin Server Pusat Akhir Pekan',
                'body' => 'Diberitahukan kepada seluruh karyawan bahwa akan dilaksanakan pemeliharaan server pusat pada hari Sabtu, 14 November pukul 22.00 WIB s/d Minggu, 15 November pukul 04.00 WIB. Selama masa pemeliharaan, beberapa sistem seperti ERP dan Presensi Mobile mungkin tidak dapat diakses sementara waktu.',
                'is_published' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(7),
                'created_by' => $creator?->id,
            ],
            [
                'title' => 'Pembaruan Kebijakan Keamanan Akun & Sandi Berkala',
                'body' => 'Sesuai standar kepatuhan ISO 27001 dan UU PDP, seluruh karyawan diwajibkan memperbarui kata sandi sistem minimal setiap 90 hari. Pastikan kata sandi baru mengandung minimal 8 karakter, huruf besar, angka, dan simbol.',
                'is_published' => true,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(14),
                'created_by' => $creator?->id,
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::updateOrCreate(['title' => $ann['title']], $ann);
        }
    }
}
