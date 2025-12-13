<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPendanaan;

class JenisPendanaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPendanaans = [
            [
                'nama' => 'Mandiri',
                'deskripsi' => 'Pendanaan mandiri dari himpunan mahasiswa',
                'is_active' => true,
            ],
            [
                'nama' => 'Sponsor',
                'deskripsi' => 'Pendanaan dari sponsor eksternal',
                'is_active' => true,
            ],
            [
                'nama' => 'Hibah',
                'deskripsi' => 'Pendanaan hibah dari lembaga/instansi',
                'is_active' => true,
            ],
            [
                'nama' => 'Internal Kampus',
                'deskripsi' => 'Pendanaan dari internal kampus/fakultas',
                'is_active' => true,
            ],
            [
                'nama' => 'Kombinasi',
                'deskripsi' => 'Kombinasi dari beberapa sumber pendanaan',
                'is_active' => true,
            ],
        ];

        foreach ($jenisPendanaans as $jenis) {
            JenisPendanaan::create($jenis);
        }
    }
}
