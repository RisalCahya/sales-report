<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create Sales Users
        User::factory()->create([
            'name' => 'Sales 1 - Budi',
            'email' => 'sales1@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        User::factory()->create([
            'name' => 'Sales 2 - Andi',
            'email' => 'sales2@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        User::factory()->create([
            'name' => 'Sales 3 - Doni',
            'email' => 'sales3@example.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
        ]);

        // Create additional sales users
        User::factory(5)->create();

        // Create backup admin account
        $this->call(AdminBackupSeeder::class);
    }
}
