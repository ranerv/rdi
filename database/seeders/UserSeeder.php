<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\College;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $college = College::first();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@crms.test',
                'password' => 'Password@123',
                'role' => 'super-admin',
                'roles' => ['super-admin'],
            ],
            [
                'name' => 'RDI Staff Member',
                'email' => 'rdi-staff@crms.test',
                'password' => 'Password@123',
                'role' => 'rdi-staff',
                'roles' => ['rdi-staff'],
            ],
            [
                'name' => 'Planning Officer',
                'email' => 'planning@crms.test',
                'password' => 'Password@123',
                'role' => 'planning-officer',
                'roles' => ['planning-officer'],
            ],
            [
                'name' => 'IPOPHL Staff',
                'email' => 'ipophl@crms.test',
                'password' => 'Password@123',
                'role' => 'ipophl-staff',
                'roles' => ['ipophl-staff'],
            ],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'proponent@crms.test',
                'password' => 'Password@123',
                'role' => 'proponent',
                'roles' => ['proponent'],
            ],
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];
            unset($userData['roles']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($userData['password']),
                    'college_id' => $college?->id,
                    'role' => $userData['role'],
                ]
            );

            $user->syncRoles($roles);
        }
    }
}
