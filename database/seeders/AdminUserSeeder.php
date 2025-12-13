<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            User::create([
                'name' => 'Super Administrator',
                'username' => 'admin',
                'email' => 'admin@sipekma.com',
                'password' => Hash::make('angkatan19'),
                'role_id' => $adminRole->id,
                'prodi_id' => null, // Admin tidak terikat prodi
                'email_verified_at' => now(),
            ]);
        }
    }
}
