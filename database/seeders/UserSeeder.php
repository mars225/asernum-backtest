<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'marsohochi@gmail.com',
            'password' => Hash::make('password'), // à changer en prod
            'mobile' => '0777247729',
            'role' => 'admin',
        ]);
    }
}
