<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admins
        User::create([
            'name' => 'Tejas V Krishna',
            'username' => 'tejas',
            'email' => 'admin@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        User::create([
            'name' => 'Amal C',
            'username' => 'amal',
            'email' => 'amal@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        User::create([
            'name' => 'Mohammad Jaish',
            'username' => 'jaish',
            'email' => 'jaish@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'reputation' => 1000,
            'bio' => 'Platform Administrator. Here to manage the knowledge base.',
        ]);

        // Moderator
        User::create([
            'name' => 'Priya Sharma',
            'username' => 'moderator',
            'email' => 'moderator@knowbase.dev',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'reputation' => 500,
            'bio' => 'Community Moderator. Keeping the platform safe and clean.',
        ]);

        // Regular users
        User::factory()->count(10)->create();
    }
}