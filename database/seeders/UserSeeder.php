<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des Admins
        $admin1 = User::create([
            'name' => 'Admin1',
            'username' => 'admin1',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('password'),
            'mobile' => '0777247729',
            'role' => 'admin',
        ]);

        $admin2 = User::create([
            'name' => 'Admin2',
            'username' => 'admin2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password'),
            'mobile' => '0777247730',
            'role' => 'admin',
        ]);

        // Création des Customers
        $customer1 = User::create([
            'name' => 'Customer1',
            'username' => 'customer1',
            'email' => 'customer1@gmail.com',
            'password' => Hash::make('password'),
            'mobile' => '0777247731',
            'role' => 'customer',
        ]);

        $customer2 = User::create([
            'name' => 'Customer2',
            'username' => 'customer2',
            'email' => 'customer2@gmail.com',
            'password' => Hash::make('password'),
            'mobile' => '0777247732',
            'role' => 'customer',
        ]);

        // Génération des tokens Sanctum
        $this->createToken($admin1, 'admin-token-1');
        $this->createToken($admin2, 'admin-token-2');
        $this->createToken($customer1, 'customer-token-1');
        $this->createToken($customer2, 'customer-token-2');
    }

    private function createToken(User $user, string $tokenName): void
    {
        $token = $user->createToken($tokenName)->plainTextToken;

        // Pour voir directement les tokens en console quand on seed
        $this->command->info("{$user->role} {$user->username} Token: {$token}");
    }
}
