<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            DepartmentSeeder::class,
            SuperAdminSeeder::class,
            UserSeeder::class,
            AppCategorySeeder::class,
            ApplicationSeeder::class,
            AccessMatrixSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
