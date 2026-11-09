<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('UserPassword123!');

        $departments = Department::pluck('id', 'code');

        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.it@nkri.co.id',
                'department_code' => 'IT',
                'role' => 'Admin Sistem',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.hr@nkri.co.id',
                'department_code' => 'HR',
                'role' => 'Admin Konten',
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.fin@nkri.co.id',
                'department_code' => 'FIN',
                'role' => 'User',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.ops@nkri.co.id',
                'department_code' => 'OPS',
                'role' => 'User',
            ],
            [
                'name' => 'Eko Kurniawan',
                'email' => 'eko.mkt@nkri.co.id',
                'department_code' => 'MKT',
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $defaultPassword,
                    'department_id' => $departments[$userData['department_code']] ?? null,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}

