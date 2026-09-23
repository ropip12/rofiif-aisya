<?php

namespace Database\Seeders;

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
        $users = [
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@aisyala.local',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'management' => null,
            ],
            [
                'name' => 'User Aset',
                'email' => 'asset.user@aisyala.local',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'management' => 'aset',
            ],
            [
                'name' => 'User Risiko',
                'email' => 'risk.user@aisyala.local',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'management' => 'risiko',
            ],
            [
                'name' => 'User Layanan',
                'email' => 'service.user@aisyala.local',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'management' => 'layanan',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                    'management' => $user['management'],
                ]
            );
        }
    }
}
