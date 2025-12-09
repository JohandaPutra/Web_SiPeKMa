<?php

namespace Database\Seeders;

use App\Models\ApprovalHistory;
use App\Models\Kegiatan;
use App\Models\KegiatanFile;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompleteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder ini membuat data lengkap untuk testing dengan 2 prodi:
     * - Sistem Informasi
     * - Teknik Informatika
     * 
     * Mencakup semua tahapan: usulan, proposal, pendanaan, laporan
     * dengan berbagai status: approved, rejected, revision, submitted, draft
     */
    public function run(): void
    {
        // Clear existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ApprovalHistory::truncate();
        KegiatanFile::truncate();
        Kegiatan::truncate();
        User::truncate();
        Prodi::truncate();
        Role::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Roles
        $roleHima = Role::create([
            'name' => 'hima',
            'display_name' => 'Himpunan Mahasiswa',
        ]);

        $rolePembina = Role::create([
            'name' => 'pembina_hima',
            'display_name' => 'Pembina Hima',
        ]);

        $roleKaprodi = Role::create([
            'name' => 'kaprodi',
            'display_name' => 'Kepala Program Studi',
        ]);

        $roleWadek = Role::create([
            'name' => 'wadek_iii',
            'display_name' => 'Wakil Dekan III',
        ]);

        // Create Prodi
        $prodiSI = Prodi::create([
            'kode_prodi' => 'SI',
            'nama_prodi' => 'Sistem Informasi',
        ]);

        $prodiTI = Prodi::create([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika',
        ]);

        // Create Users

        // Hima SI
        $himaSI = User::create([
            'username' => 'hima_si',
            'name' => 'Hima Sistem Informasi',
            'email' => 'hima_si@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleHima->id,
            'prodi_id' => $prodiSI->id,
        ]);

        // Hima TI
        $himaTI = User::create([
            'username' => 'hima_ti',
            'name' => 'Hima Teknik Informatika',
            'email' => 'hima_ti@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleHima->id,
            'prodi_id' => $prodiTI->id,
        ]);

        // Pembina SI
        $pembinaSI = User::create([
            'username' => 'pembina_si',
            'name' => 'Pembina SI',
            'email' => 'pembina_si@example.com',
            'password' => Hash::make('password'),
            'role_id' => $rolePembina->id,
            'prodi_id' => $prodiSI->id,
        ]);

        // Pembina TI
        $pembinaTI = User::create([
            'username' => 'pembina_ti',
            'name' => 'Pembina TI',
            'email' => 'pembina_ti@example.com',
            'password' => Hash::make('password'),
            'role_id' => $rolePembina->id,
            'prodi_id' => $prodiTI->id,
        ]);

        // Kaprodi SI
        $kaprodiSI = User::create([
            'username' => 'kaprodi_si',
            'name' => 'Kaprodi Sistem Informasi',
            'email' => 'kaprodi_si@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleKaprodi->id,
            'prodi_id' => $prodiSI->id,
        ]);

        // Kaprodi TI
        $kaprodiTI = User::create([
            'username' => 'kaprodi_ti',
            'name' => 'Kaprodi Teknik Informatika',
            'email' => 'kaprodi_ti@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleKaprodi->id,
            'prodi_id' => $prodiTI->id,
        ]);

        // Wadek III (untuk semua prodi)
        $wadek = User::create([
            'username' => 'wadek_iii',
            'name' => 'Wakil Dekan III',
            'email' => 'wadek3@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roleWadek->id,
            'prodi_id' => $prodiSI->id, // Wadek bisa akses semua prodi
        ]);

        // ==================== SISTEM INFORMASI ====================

        // 1. Kegiatan SI - DRAFT (Usulan belum disubmit)
        $kegiatanSI1 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Workshop Data Science dan AI',
            'deskripsi_kegiatan' => 'Workshop tentang penerapan data science dan artificial intelligence dalam industri.',
            'jenis_kegiatan' => 'workshop',
            'tempat_kegiatan' => 'Lab Komputer SI Gedung C',
            'tanggal_mulai' => '2026-02-15',
            'tanggal_akhir' => '2026-02-15',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 5000000,
            'tahap' => 'usulan',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);

        // 2. Kegiatan SI - REVISION (Usulan perlu revisi dari Pembina)
        $kegiatanSI2 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Seminar Cyber Security',
            'deskripsi_kegiatan' => 'Seminar tentang keamanan siber dan perlindungan data.',
            'jenis_kegiatan' => 'seminar',
            'tempat_kegiatan' => 'Aula SI Lantai 3',
            'tanggal_mulai' => '2026-03-10',
            'tanggal_akhir' => '2026-03-10',
            'jenis_pendanaan' => 'sponsor',
            'total_anggaran' => 8000000,
            'tahap' => 'usulan',
            'status' => 'revision',
            'current_approver_role' => 'pembina_hima',
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI2->id,
            'approver_user_id' => $pembinaSI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'usulan',
            'action' => 'revision',
            'comment' => 'Mohon lengkapi detail jadwal acara dan susunan panitia.',
            'approved_at' => now()->subDays(2),
        ]);

        // 3. Kegiatan SI - SUBMITTED (Menunggu approval Kaprodi)
        $kegiatanSI3 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Lomba Analisis Big Data',
            'deskripsi_kegiatan' => 'Kompetisi analisis big data dengan dataset real dari industri.',
            'jenis_kegiatan' => 'lomba',
            'tempat_kegiatan' => 'Online via Zoom',
            'tanggal_mulai' => '2026-04-20',
            'tanggal_akhir' => '2026-04-21',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 12000000,
            'tahap' => 'usulan',
            'status' => 'submitted',
            'current_approver_role' => 'kaprodi',
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI3->id,
            'approver_user_id' => $pembinaSI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Usulan sudah bagus, disetujui.',
            'approved_at' => now()->subDays(3),
        ]);

        // 4. Kegiatan SI - REJECTED (Usulan ditolak oleh Wadek)
        $kegiatanSI4 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Study Tour ke Silicon Valley',
            'deskripsi_kegiatan' => 'Kunjungan industri ke perusahaan teknologi di Silicon Valley.',
            'jenis_kegiatan' => 'lainnya',
            'tempat_kegiatan' => 'Silicon Valley, USA',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_akhir' => '2026-07-10',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 150000000,
            'tahap' => 'usulan',
            'status' => 'rejected',
            'current_approver_role' => null,
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI4->id,
            'approver_user_id' => $pembinaSI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Disetujui untuk proses lebih lanjut.',
            'approved_at' => now()->subDays(10),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI4->id,
            'approver_user_id' => $kaprodiSI->id,
            'approver_role' => 'kaprodi',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Program bagus untuk mahasiswa.',
            'approved_at' => now()->subDays(9),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI4->id,
            'approver_user_id' => $wadek->id,
            'approver_role' => 'wadek_iii',
            'tahap' => 'usulan',
            'action' => 'rejected',
            'comment' => 'Anggaran terlalu besar dan tidak sesuai dengan prioritas fakultas tahun ini.',
            'approved_at' => now()->subDays(8),
        ]);

        // 5. Kegiatan SI - PROPOSAL (Usulan approved, masuk tahap proposal - draft)
        $kegiatanSI5 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Pelatihan UI/UX Design',
            'deskripsi_kegiatan' => 'Pelatihan desain antarmuka pengguna dan pengalaman pengguna untuk aplikasi mobile.',
            'jenis_kegiatan' => 'pelatihan',
            'tempat_kegiatan' => 'Lab Multimedia SI',
            'tanggal_mulai' => '2026-03-25',
            'tanggal_akhir' => '2026-03-26',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 7000000,
            'tahap' => 'proposal',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);

        // Approval history usulan (sudah approved semua)
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI5->id,
            'approver_user_id' => $pembinaSI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Usulan disetujui.',
            'approved_at' => now()->subDays(15),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI5->id,
            'approver_user_id' => $kaprodiSI->id,
            'approver_role' => 'kaprodi',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Disetujui.',
            'approved_at' => now()->subDays(14),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI5->id,
            'approver_user_id' => $wadek->id,
            'approver_role' => 'wadek_iii',
            'tahap' => 'usulan',
            'action' => 'approved',
            'comment' => 'Disetujui untuk lanjut ke proposal.',
            'approved_at' => now()->subDays(13),
        ]);

        // 6. Kegiatan SI - PROPOSAL SUBMITTED (Proposal sedang direview)
        $kegiatanSI6 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Hackathon Smart City Solutions',
            'deskripsi_kegiatan' => 'Kompetisi pemrograman 24 jam dengan tema solusi kota cerdas.',
            'jenis_kegiatan' => 'lomba',
            'tempat_kegiatan' => 'Lab Komputer 1 & 2',
            'tanggal_mulai' => '2026-05-15',
            'tanggal_akhir' => '2026-05-16',
            'jenis_pendanaan' => 'sponsor',
            'total_anggaran' => 15000000,
            'tahap' => 'proposal',
            'status' => 'submitted',
            'current_approver_role' => 'pembina_hima',
        ]);

        // Approval usulan
        foreach ([$pembinaSI, $kaprodiSI, $wadek] as $approver) {
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatanSI6->id,
                'approver_user_id' => $approver->id,
                'approver_role' => $approver->role->name,
                'tahap' => 'usulan',
                'action' => 'approved',
                'comment' => 'Disetujui.',
                'approved_at' => now()->subDays(20),
            ]);
        }

        // File proposal uploaded
        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI6->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Hackathon_Smart_City.pdf',
            'file_path' => 'proposals/hackathon_smart_city.pdf',
            'file_size' => 2500000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(1),
        ]);

        // 7. Kegiatan SI - PENDANAAN REJECTED (Proposal approved, RAB ditolak)
        $kegiatanSI7 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Webinar Cloud Computing',
            'deskripsi_kegiatan' => 'Webinar tentang teknologi cloud computing dan implementasinya.',
            'jenis_kegiatan' => 'seminar',
            'tempat_kegiatan' => 'Online via Google Meet',
            'tanggal_mulai' => '2026-04-05',
            'tanggal_akhir' => '2026-04-05',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 3000000,
            'tahap' => 'pendanaan',
            'status' => 'rejected',
            'current_approver_role' => null,
        ]);

        // Approval usulan
        foreach ([$pembinaSI, $kaprodiSI, $wadek] as $approver) {
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatanSI7->id,
                'approver_user_id' => $approver->id,
                'approver_role' => $approver->role->name,
                'tahap' => 'usulan',
                'action' => 'approved',
                'comment' => 'Disetujui.',
                'approved_at' => now()->subDays(25),
            ]);
        }

        // Approval proposal
        foreach ([$pembinaSI, $kaprodiSI, $wadek] as $approver) {
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatanSI7->id,
                'approver_user_id' => $approver->id,
                'approver_role' => $approver->role->name,
                'tahap' => 'proposal',
                'action' => 'approved',
                'comment' => 'Proposal disetujui.',
                'approved_at' => now()->subDays(20),
            ]);
        }

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI7->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Webinar_Cloud.pdf',
            'file_path' => 'proposals/webinar_cloud.pdf',
            'file_size' => 1800000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(21),
        ]);

        // RAB uploaded tapi ditolak
        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI7->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Webinar_Cloud.xlsx',
            'file_path' => 'rab/webinar_cloud.xlsx',
            'file_size' => 150000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(15),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI7->id,
            'approver_user_id' => $pembinaSI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'pendanaan',
            'action' => 'approved',
            'comment' => 'RAB sudah sesuai.',
            'approved_at' => now()->subDays(14),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanSI7->id,
            'approver_user_id' => $kaprodiSI->id,
            'approver_role' => 'kaprodi',
            'tahap' => 'pendanaan',
            'action' => 'rejected',
            'comment' => 'Anggaran untuk konsumsi terlalu tinggi. Mohon disesuaikan dengan standar fakultas.',
            'approved_at' => now()->subDays(13),
        ]);

        // 8. Kegiatan SI - LAPORAN SUBMITTED (Kegiatan selesai, laporan sedang direview)
        $kegiatanSI8 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Workshop Database Management',
            'deskripsi_kegiatan' => 'Workshop tentang pengelolaan database dan optimasi query.',
            'jenis_kegiatan' => 'workshop',
            'tempat_kegiatan' => 'Lab Database SI',
            'tanggal_mulai' => '2026-01-20',
            'tanggal_akhir' => '2026-01-20',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 4000000,
            'tahap' => 'laporan',
            'status' => 'submitted',
            'current_approver_role' => 'pembina_hima',
        ]);

        // Complete all previous approvals
        foreach ([$pembinaSI, $kaprodiSI, $wadek] as $approver) {
            foreach (['usulan', 'proposal', 'pendanaan'] as $tahap) {
                ApprovalHistory::create([
                    'kegiatan_id' => $kegiatanSI8->id,
                    'approver_user_id' => $approver->id,
                    'approver_role' => $approver->role->name,
                    'tahap' => $tahap,
                    'action' => 'approved',
                    'comment' => 'Disetujui.',
                    'approved_at' => now()->subDays(30),
                ]);
            }
        }

        // Files
        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI8->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Workshop_DB.pdf',
            'file_path' => 'proposals/workshop_db.pdf',
            'file_size' => 2000000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(35),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI8->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Workshop_DB.xlsx',
            'file_path' => 'rab/workshop_db.xlsx',
            'file_size' => 120000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(32),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI8->id,
            'tahap' => 'laporan',
            'file_type' => 'laporan',
            'file_name' => 'Laporan_Workshop_DB.pdf',
            'file_path' => 'laporan/workshop_db.pdf',
            'file_size' => 3000000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(2),
        ]);

        // 9. Kegiatan SI - COMPLETED (Semua tahap selesai)
        $kegiatanSI9 = Kegiatan::create([
            'user_id' => $himaSI->id,
            'prodi_id' => $prodiSI->id,
            'nama_kegiatan' => 'Seminar Blockchain Technology',
            'deskripsi_kegiatan' => 'Seminar tentang teknologi blockchain dan cryptocurrency.',
            'jenis_kegiatan' => 'seminar',
            'tempat_kegiatan' => 'Aula SI',
            'tanggal_mulai' => '2025-12-01',
            'tanggal_akhir' => '2025-12-01',
            'jenis_pendanaan' => 'sponsor',
            'total_anggaran' => 10000000,
            'tahap' => 'laporan',
            'status' => 'submitted',
            'current_approver_role' => 'completed',
        ]);

        // Complete all approvals including laporan
        foreach ([$pembinaSI, $kaprodiSI, $wadek] as $approver) {
            foreach (['usulan', 'proposal', 'pendanaan', 'laporan'] as $tahap) {
                ApprovalHistory::create([
                    'kegiatan_id' => $kegiatanSI9->id,
                    'approver_user_id' => $approver->id,
                    'approver_role' => $approver->role->name,
                    'tahap' => $tahap,
                    'action' => 'approved',
                    'comment' => 'Disetujui.',
                    'approved_at' => now()->subDays(40),
                ]);
            }
        }

        // All files
        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI9->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Seminar_Blockchain.pdf',
            'file_path' => 'proposals/seminar_blockchain.pdf',
            'file_size' => 2200000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(50),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI9->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Seminar_Blockchain.xlsx',
            'file_path' => 'rab/seminar_blockchain.xlsx',
            'file_size' => 130000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(48),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanSI9->id,
            'tahap' => 'laporan',
            'file_type' => 'laporan',
            'file_name' => 'Laporan_Seminar_Blockchain.pdf',
            'file_path' => 'laporan/seminar_blockchain.pdf',
            'file_size' => 3500000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(42),
        ]);

        // ==================== TEKNIK INFORMATIKA ====================

        // 10. Kegiatan TI - DRAFT
        $kegiatanTI1 = Kegiatan::create([
            'user_id' => $himaTI->id,
            'prodi_id' => $prodiTI->id,
            'nama_kegiatan' => 'Pelatihan Mobile App Development dengan Flutter',
            'deskripsi_kegiatan' => 'Pelatihan pengembangan aplikasi mobile menggunakan framework Flutter.',
            'jenis_kegiatan' => 'pelatihan',
            'tempat_kegiatan' => 'Lab Mobile TI',
            'tanggal_mulai' => '2026-03-15',
            'tanggal_akhir' => '2026-03-17',
            'jenis_pendanaan' => 'sponsor',
            'total_anggaran' => 8500000,
            'tahap' => 'usulan',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);

        // 11. Kegiatan TI - REVISION (Proposal perlu revisi)
        $kegiatanTI2 = Kegiatan::create([
            'user_id' => $himaTI->id,
            'prodi_id' => $prodiTI->id,
            'nama_kegiatan' => 'Lomba Game Development',
            'deskripsi_kegiatan' => 'Kompetisi pengembangan game dengan tema edukasi.',
            'jenis_kegiatan' => 'lomba',
            'tempat_kegiatan' => 'Lab Game TI',
            'tanggal_mulai' => '2026-05-10',
            'tanggal_akhir' => '2026-05-12',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 12000000,
            'tahap' => 'proposal',
            'status' => 'revision',
            'current_approver_role' => 'kaprodi',
        ]);

        // Usulan approved
        foreach ([$pembinaTI, $kaprodiTI, $wadek] as $approver) {
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatanTI2->id,
                'approver_user_id' => $approver->id,
                'approver_role' => $approver->role->name,
                'tahap' => 'usulan',
                'action' => 'approved',
                'comment' => 'Disetujui.',
                'approved_at' => now()->subDays(18),
            ]);
        }

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI2->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Lomba_Game.pdf',
            'file_path' => 'proposals/lomba_game.pdf',
            'file_size' => 2100000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(16),
        ]);

        // Proposal revision from Pembina
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanTI2->id,
            'approver_user_id' => $pembinaTI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'proposal',
            'action' => 'revision',
            'comment' => 'Proposal perlu ditambahkan rincian juri dan kriteria penilaian lomba.',
            'approved_at' => now()->subDays(3),
        ]);

        // 12. Kegiatan TI - PENDANAAN SUBMITTED
        $kegiatanTI3 = Kegiatan::create([
            'user_id' => $himaTI->id,
            'prodi_id' => $prodiTI->id,
            'nama_kegiatan' => 'Workshop IoT dan Smart Home',
            'deskripsi_kegiatan' => 'Workshop tentang Internet of Things dan aplikasi smart home.',
            'jenis_kegiatan' => 'workshop',
            'tempat_kegiatan' => 'Lab IoT TI',
            'tanggal_mulai' => '2026-04-08',
            'tanggal_akhir' => '2026-04-09',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 6000000,
            'tahap' => 'pendanaan',
            'status' => 'submitted',
            'current_approver_role' => 'pembina_hima',
        ]);

        // Complete usulan and proposal
        foreach ([$pembinaTI, $kaprodiTI, $wadek] as $approver) {
            foreach (['usulan', 'proposal'] as $tahap) {
                ApprovalHistory::create([
                    'kegiatan_id' => $kegiatanTI3->id,
                    'approver_user_id' => $approver->id,
                    'approver_role' => $approver->role->name,
                    'tahap' => $tahap,
                    'action' => 'approved',
                    'comment' => 'Disetujui.',
                    'approved_at' => now()->subDays(22),
                ]);
            }
        }

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI3->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Workshop_IoT.pdf',
            'file_path' => 'proposals/workshop_iot.pdf',
            'file_size' => 2300000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(23),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI3->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Workshop_IoT.xlsx',
            'file_path' => 'rab/workshop_iot.xlsx',
            'file_size' => 145000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(2),
        ]);

        // 13. Kegiatan TI - LAPORAN REVISION
        $kegiatanTI4 = Kegiatan::create([
            'user_id' => $himaTI->id,
            'prodi_id' => $prodiTI->id,
            'nama_kegiatan' => 'Seminar Nasional Teknologi Informasi 2025',
            'deskripsi_kegiatan' => 'Seminar nasional tentang perkembangan terkini teknologi informasi.',
            'jenis_kegiatan' => 'seminar',
            'tempat_kegiatan' => 'Auditorium Utama',
            'tanggal_mulai' => '2025-11-15',
            'tanggal_akhir' => '2025-11-15',
            'jenis_pendanaan' => 'sponsor',
            'total_anggaran' => 25000000,
            'tahap' => 'laporan',
            'status' => 'revision',
            'current_approver_role' => 'pembina_hima',
        ]);

        // Complete all previous stages
        foreach ([$pembinaTI, $kaprodiTI, $wadek] as $approver) {
            foreach (['usulan', 'proposal', 'pendanaan'] as $tahap) {
                ApprovalHistory::create([
                    'kegiatan_id' => $kegiatanTI4->id,
                    'approver_user_id' => $approver->id,
                    'approver_role' => $approver->role->name,
                    'tahap' => $tahap,
                    'action' => 'approved',
                    'comment' => 'Disetujui.',
                    'approved_at' => now()->subDays(45),
                ]);
            }
        }

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI4->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Semnas_TI.pdf',
            'file_path' => 'proposals/semnas_ti.pdf',
            'file_size' => 3000000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(50),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI4->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Semnas_TI.xlsx',
            'file_path' => 'rab/semnas_ti.xlsx',
            'file_size' => 180000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(48),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI4->id,
            'tahap' => 'laporan',
            'file_type' => 'laporan',
            'file_name' => 'Laporan_Semnas_TI.pdf',
            'file_path' => 'laporan/semnas_ti.pdf',
            'file_size' => 4000000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(5),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanTI4->id,
            'approver_user_id' => $pembinaTI->id,
            'approver_role' => 'pembina_hima',
            'tahap' => 'laporan',
            'action' => 'revision',
            'comment' => 'Laporan perlu dilengkapi dengan dokumentasi foto kegiatan dan daftar hadir peserta.',
            'approved_at' => now()->subDays(3),
        ]);

        // 14. Kegiatan TI - COMPLETED
        $kegiatanTI5 = Kegiatan::create([
            'user_id' => $himaTI->id,
            'prodi_id' => $prodiTI->id,
            'nama_kegiatan' => 'Tech Talk: Career Path in IT Industry',
            'deskripsi_kegiatan' => 'Seminar tentang berbagai jalur karir di industri IT dengan pembicara dari berbagai perusahaan.',
            'jenis_kegiatan' => 'seminar',
            'tempat_kegiatan' => 'Aula Prodi Informatika',
            'tanggal_mulai' => '2025-10-20',
            'tanggal_akhir' => '2025-10-20',
            'jenis_pendanaan' => 'internal',
            'total_anggaran' => 5000000,
            'tahap' => 'laporan',
            'status' => 'submitted',
            'current_approver_role' => 'completed',
        ]);

        // Complete all stages
        foreach ([$pembinaTI, $kaprodiTI, $wadek] as $approver) {
            foreach (['usulan', 'proposal', 'pendanaan', 'laporan'] as $tahap) {
                ApprovalHistory::create([
                    'kegiatan_id' => $kegiatanTI5->id,
                    'approver_user_id' => $approver->id,
                    'approver_role' => $approver->role->name,
                    'tahap' => $tahap,
                    'action' => 'approved',
                    'comment' => 'Disetujui.',
                    'approved_at' => now()->subDays(60),
                ]);
            }
        }

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI5->id,
            'tahap' => 'proposal',
            'file_type' => 'proposal',
            'file_name' => 'Proposal_Tech_Talk.pdf',
            'file_path' => 'proposals/tech_talk.pdf',
            'file_size' => 1900000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(65),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI5->id,
            'tahap' => 'pendanaan',
            'file_type' => 'rab',
            'file_name' => 'RAB_Tech_Talk.xlsx',
            'file_path' => 'rab/tech_talk.xlsx',
            'file_size' => 125000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(63),
        ]);

        KegiatanFile::create([
            'kegiatan_id' => $kegiatanTI5->id,
            'tahap' => 'laporan',
            'file_type' => 'laporan',
            'file_name' => 'Laporan_Tech_Talk.pdf',
            'file_path' => 'laporan/tech_talk.pdf',
            'file_size' => 3200000,

            'uploaded_by' => $himaSI->id,

            'uploaded_at' => now()->subDays(62),
        ]);

        $this->command->info('✅ Complete test seeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->info('=== DATA YANG DIBUAT ===');
        $this->command->info('👥 Users: 8 (2 Hima, 2 Pembina, 2 Kaprodi, 1 Wadek)');
        $this->command->info('🏫 Prodi: 2 (Sistem Informasi, Teknik Informatika)');
        $this->command->info('📋 Kegiatan: 14 total');
        $this->command->info('   SI: 9 kegiatan (draft, revision, submitted, rejected, proposal stages, pendanaan rejected, laporan, completed)');
        $this->command->info('   TI: 5 kegiatan (draft, proposal revision, pendanaan submitted, laporan revision, completed)');
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS (password: password) ===');
        $this->command->info('Hima SI      : hima_si@example.com');
        $this->command->info('Hima TI      : hima_ti@example.com');
        $this->command->info('Pembina SI   : pembina_si@example.com');
        $this->command->info('Pembina TI   : pembina_ti@example.com');
        $this->command->info('Kaprodi SI   : kaprodi_si@example.com');
        $this->command->info('Kaprodi TI   : kaprodi_ti@example.com');
        $this->command->info('Wadek III    : wadek3@example.com');
    }
}
