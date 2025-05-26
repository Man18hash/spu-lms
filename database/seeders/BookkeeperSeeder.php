<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BookkeeperSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'bookkeeper@lms.com'],
            [
                'name'        => 'Bookkeeper',
                'employee_id' => 'BK-001',
                'dob'         => now()->subYears(30),
                'address'     => 'Head Office',
                'password'    => Hash::make('password123'),
                'role'        => 'bookkeeper',
            ]
        );
    }
}
