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
        // Create 9 random users

        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Tian',
            'surname' => 'Tian',
            'email' => 'tian@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->count(20)->create();
    }
}
