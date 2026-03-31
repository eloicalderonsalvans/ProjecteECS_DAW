<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Admin',
            'cognom' => 'CognomAdmin',
            'email' => 'admin@example.com',
            'contrassenya' => Hash::make('password'),
            'DNI' => '12345678A',
            'data_alta' => now(),
            'actiu' => true,
            'department_id' => null,
        ]);
    }
}
