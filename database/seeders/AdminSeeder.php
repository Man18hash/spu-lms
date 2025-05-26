<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@lms.com'],
            [
                'name'        => 'Administrator',
                'employee_id' => 'ADM-001',
                'dob'         => now()->subYears(35),
                'address'     => 'Head Office',
                'password'    => Hash::make('password123'),
                'role'        => 'admin',
            ]
        );
    }
}
