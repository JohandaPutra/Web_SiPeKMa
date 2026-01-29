<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator'],
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'wadek_iii', 'display_name' => 'Wakil Dekan III'],
            ['name' => 'kaprodi', 'display_name' => 'Kepala Program Studi'],
            ['name' => 'pembina_hima', 'display_name' => 'Pembina Hima'],
            ['name' => 'hima', 'display_name' => 'Himpunan Mahasiswa'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['display_name' => $role['display_name']]
            );
        }
    }
}
