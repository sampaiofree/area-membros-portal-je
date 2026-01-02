<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\CertificateBranding;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@edux.test',
                'role' => UserRole::ADMIN,
            ],
            [
                'name' => 'Aluno Diego',
                'email' => 'aluno@edux.test',
                'role' => UserRole::STUDENT,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role' => $userData['role']->value,
                    'email_verified_at' => now(),
                ]
            );
        }

        CertificateBranding::firstOrCreate(['course_id' => null]);

        $this->call(CourseSeeder::class);
    }
}
