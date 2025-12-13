<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      RoleSeeder::class,
      ProdiSeeder::class,
      JenisKegiatanSeeder::class,
      JenisPendanaanSeeder::class,
      AdminUserSeeder::class,
      SistemInformasiTestSeeder::class, // Test data untuk 1 prodi dengan 12 kegiatan lengkap
    ]);
  }
}
