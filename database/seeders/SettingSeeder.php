<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set(
            'hero_title',
            'Selamat datang di Portal NKRI',
            'string'
        );

        Setting::set(
            'hero_description',
            'Penyimpan seluruh sistem yang dibuat untuk PT NKRI',
            'string'
        );

        Setting::set(
            'footer_text',
            'PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.',
            'string'
        );
    }
}
