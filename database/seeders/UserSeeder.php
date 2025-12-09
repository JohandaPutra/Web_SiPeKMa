<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
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
        // Get roles
        $himaRole = Role::where('name', 'hima')->first();
        $pembinaRole = Role::where('name', 'pembina_hima')->first();
        $kaprodiRole = Role::where('name', 'kaprodi')->first();
        $wadekRole = Role::where('name', 'wadek_iii')->first();

        // Get prodis
        $prodis = Prodi::all();

        // Create users for each prodi
        foreach ($prodis as $prodi) {
            // Hima user - Format: nama prodi saja
            User::create([
                'name' => $prodi->nama_prodi, // Informatika, Sistem Informasi, Teknik Komputer
                'username' => 'hima_' . strtolower($prodi->kode_prodi),
                'email' => 'hima.' . strtolower($prodi->kode_prodi) . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $himaRole->id,
                'prodi_id' => $prodi->id,
            ]);

            // Pembina Hima user - Format: Pembina [Nama Prodi]
            User::create([
                'name' => 'Pembina ' . $prodi->nama_prodi,
                'username' => 'pembina_' . strtolower($prodi->kode_prodi),
                'email' => 'pembina.' . strtolower($prodi->kode_prodi) . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $pembinaRole->id,
                'prodi_id' => $prodi->id,
            ]);

            // Kaprodi user - Format: Kaprodi [Nama Prodi]
            User::create([
                'name' => 'Kaprodi ' . $prodi->nama_prodi,
                'username' => 'kaprodi_' . strtolower($prodi->kode_prodi),
                'email' => 'kaprodi.' . strtolower($prodi->kode_prodi) . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $kaprodiRole->id,
                'prodi_id' => $prodi->id,
            ]);
        }

        // Wadek III (admin) - hanya 1 untuk semua prodi
        User::create([
            'name' => 'Wakil Dekan III',
            'username' => 'wadek_iii',
            'email' => 'wadek.iii@example.com',
            'password' => Hash::make('password'),
            'role_id' => $wadekRole->id,
            'prodi_id' => null, // Wadek tidak terikat prodi tertentu
        ]);
    }
}
