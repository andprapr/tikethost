<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@eventhokilalas89.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}