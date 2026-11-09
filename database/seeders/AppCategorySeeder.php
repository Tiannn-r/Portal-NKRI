<?php

namespace Database\Seeders;

use App\Models\AppCategory;
use Illuminate\Database\Seeder;

class AppCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'SDM & Kepegawaian',
                'slug' => 'sdm-kepegawaian',
                'sort_order' => 1,
            ],
            [
                'name' => 'Finansial & Akuntansi',
                'slug' => 'finansial-akuntansi',
                'sort_order' => 2,
            ],
            [
                'name' => 'Operasional & Rantai Pasok',
                'slug' => 'operasional-rantai-pasok',
                'sort_order' => 3,
            ],
            [
                'name' => 'Penjualan & Pelanggan',
                'slug' => 'penjualan-pelanggan',
                'sort_order' => 4,
            ],
            [
                'name' => 'Produktivitas & Kolaborasi',
                'slug' => 'produktivitas-kolaborasi',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            AppCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}

