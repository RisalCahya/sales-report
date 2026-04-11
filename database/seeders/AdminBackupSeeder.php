<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminBackupSeeder extends Seeder
{
    /**
     * Seed a backup admin account.
     * Run standalone: php artisan db:seed --class=AdminBackupSeeder
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.backup@ake.local'],
            [
                'name'      => 'Admin Cadangan',
                'email'     => 'admin.backup@ake.local',
                'password'  => Hash::make('AKE@backup2026!'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        $this->command->info('Akun admin cadangan berhasil dibuat/diperbarui.');
        $this->command->warn('Email : admin.backup@ake.local');
        $this->command->warn('Pass  : AKE@backup2026!');
        $this->command->warn('Segera ganti password setelah pertama login!');
    }
}
