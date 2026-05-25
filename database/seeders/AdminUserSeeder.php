<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin exists
        if (!User::where('email', 'admin@eserianhomes.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@eserianhomes.com',
                'password' => Hash::make('admin123'),
                'phone' => '254700000000',
                'role' => 'admin'
            ]);
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}