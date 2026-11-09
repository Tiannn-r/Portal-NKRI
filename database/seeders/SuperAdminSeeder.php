<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = env('SUPER_ADMIN_NAME', 'Super Administrator');
        $email = env('SUPER_ADMIN_EMAIL', 'admin@nkri.co.id');
        $password = env('SUPER_ADMIN_PASSWORD', 'PortalPassword123!');

        $itDept = Department::where('code', 'IT')->first();

        $superAdmin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'department_id' => $itDept?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Berikan role Super Admin
        $superAdmin->syncRoles(['Super Admin']);
    }
}
