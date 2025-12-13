<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisKegiatan;

class JenisKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKegiatans = [
            [
                'nama' => 'Seminar',
                'deskripsi' => 'Kegiatan seminar akademik atau non-akademik',
                'is_active' => true,
            ],
            [
                'nama' => 'Workshop',
                'deskripsi' => 'Kegiatan workshop pelatihan dan pengembangan skill',
                'is_active' => true,
            ],
            [
                'nama' => 'Pelatihan',
                'deskripsi' => 'Kegiatan pelatihan untuk meningkatkan kompetensi mahasiswa',
                'is_active' => true,
            ],
            [
                'nama' => 'Lomba',
                'deskripsi' => 'Kegiatan perlombaan atau kompetisi',
                'is_active' => true,
            ],
            [
                'nama' => 'Webinar',
                'deskripsi' => 'Seminar online melalui platform digital',
                'is_active' => true,
            ],
            [
                'nama' => 'Study Tour',
                'deskripsi' => 'Kegiatan kunjungan edukatif',
                'is_active' => true,
            ],
            [
                'nama' => 'Lainnya',
                'deskripsi' => 'Jenis kegiatan lainnya yang tidak termasuk kategori di atas',
                'is_active' => true,
            ],
        ];

        foreach ($jenisKegiatans as $jenis) {
            JenisKegiatan::create($jenis);
        }
    }
}
