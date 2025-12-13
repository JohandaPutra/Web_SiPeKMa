<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use App\Models\JenisKegiatan;
use App\Models\JenisPendanaan;
use App\Models\ApprovalHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SistemInformasiTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder untuk testing dengan 1 prodi: Sistem Informasi
     * Includes semua role dan kegiatan dengan approval histories yang benar
     */
    public function run(): void
    {
        // Get existing data
        $prodi = Prodi::where('nama_prodi', 'Sistem Informasi')->first();
        if (!$prodi) {
            $this->command->error('Prodi Sistem Informasi tidak ditemukan!');
            return;
        }

        // Get all roles
        $roleHima = Role::where('name', 'hima')->first();
        $rolePembina = Role::where('name', 'pembina_hima')->first();
        $roleKaprodi = Role::where('name', 'kaprodi')->first();
        $roleWadek = Role::where('name', 'wadek_iii')->first();

        // Get jenis kegiatan dan pendanaan untuk variasi
        $jenisKegiatans = JenisKegiatan::all();
        $jenisPendanaans = JenisPendanaan::all();

        // Create users untuk Sistem Informasi
        $himaUser = User::create([
            'name' => 'HIMA Sistem Informasi',
            'username' => 'hima_si',
            'email' => 'hima.si@unja.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleHima->id,
            'prodi_id' => $prodi->id,
        ]);

        $pembinaUser = User::create([
            'name' => 'Pembina HIMA SI',
            'username' => 'pembina_si',
            'email' => 'pembina.si@unja.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $rolePembina->id,
            'prodi_id' => $prodi->id,
        ]);

        $kaprodiUser = User::create([
            'name' => 'Kaprodi Sistem Informasi',
            'username' => 'kaprodi_si',
            'email' => 'kaprodi.si@unja.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleKaprodi->id,
            'prodi_id' => $prodi->id,
        ]);

        $wadekUser = User::create([
            'name' => 'Wadek III',
            'username' => 'wadek_iii',
            'email' => 'wadek.iii@unja.ac.id',
            'password' => Hash::make('password'),
            'role_id' => $roleWadek->id,
            'prodi_id' => null, // Wadek lintas prodi
        ]);

        $this->command->info('✅ Users created: HIMA, Pembina, Kaprodi, Wadek III');

        // === TAHAP USULAN (4 kegiatan dengan berbagai status) ===

        // Usulan 1: Draft (belum submit)
        Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Workshop Web Development Dasar',
            'deskripsi_kegiatan' => 'Workshop untuk mahasiswa baru tentang dasar-dasar web development.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Workshop')->first()->id,
            'tempat_kegiatan' => 'Lab Komputer Gedung C',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Mandiri')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(30),
            'tanggal_akhir' => Carbon::now()->addDays(31),
            'tahap' => 'usulan',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);

        // Usulan 2: Dikirim - Menunggu Pembina
        $kegiatan2 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Seminar Cyber Security',
            'deskripsi_kegiatan' => 'Seminar mengenai keamanan siber di era digital.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Seminar')->first()->id,
            'tempat_kegiatan' => 'Aula Fakultas MIPA',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Sponsor')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(35),
            'tanggal_akhir' => Carbon::now()->addDays(35),
            'tahap' => 'usulan',
            'status' => 'dikirim',
            'current_approver_role' => 'pembina_hima',
                    ]);

        // Usulan 3: Disetujui Pembina, Menunggu Kaprodi
        $kegiatan3 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Pelatihan UI/UX Design',
            'deskripsi_kegiatan' => 'Pelatihan UI/UX menggunakan Figma.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Pelatihan')->first()->id,
            'tempat_kegiatan' => 'Ruang Multimedia',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Hibah')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(40),
            'tanggal_akhir' => Carbon::now()->addDays(42),
            'tahap' => 'usulan',
            'status' => 'dikirim',
            'current_approver_role' => 'kaprodi',
                    ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan3->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Usulan bagus, sangat bermanfaat untuk mahasiswa.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(4),
        ]);

        // Usulan 4: Disetujui Pembina & Kaprodi, Menunggu Wadek
        $kegiatan4 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Lomba Programming Contest',
            'deskripsi_kegiatan' => 'Kompetisi programming tingkat fakultas.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Lomba')->first()->id,
            'tempat_kegiatan' => 'Lab Komputer',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Internal Kampus')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(45),
            'tanggal_akhir' => Carbon::now()->addDays(45),
            'tahap' => 'usulan',
            'status' => 'dikirim',
            'current_approver_role' => 'wadek_iii',
                    ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan4->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Kegiatan lomba ini sangat baik untuk melatih skill mahasiswa.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(6),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan4->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Setuju, kegiatan lomba ini dapat meningkatkan prestasi prodi.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(5),
        ]);

        $this->command->info('✅ Tahap Usulan: 4 kegiatan (draft, dikirim, proses approval)');

        // === TAHAP PROPOSAL (3 kegiatan) ===

        // Proposal 1: Draft (Usulan sudah approved, belum upload proposal)
        $kegiatan6 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Webinar Cloud Computing AWS',
            'deskripsi_kegiatan' => 'Webinar online cloud computing.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Webinar')->first()->id,
            'tempat_kegiatan' => 'Online - Zoom',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Kombinasi')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(55),
            'tanggal_akhir' => Carbon::now()->addDays(55),
            'tahap' => 'proposal',
            'status' => 'draft',
            'current_approver_role' => null,
                    ]);

        // Approval histories usulan
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan6->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Webinar cloud computing sangat relevan.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(11),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan6->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Topik AWS sangat dibutuhkan mahasiswa.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(10),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan6->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Disetujui untuk usulan. Lanjut proposal.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(9),
        ]);

        // Proposal 2: Dikirim - Menunggu Pembina (Proposal sudah diupload)
        $kegiatan7 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Workshop Git & GitHub',
            'deskripsi_kegiatan' => 'Workshop version control dengan Git.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Workshop')->first()->id,
            'tempat_kegiatan' => 'Lab Komputer',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Mandiri')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(60),
            'tanggal_akhir' => Carbon::now()->addDays(60),
            'tahap' => 'proposal',
            'status' => 'dikirim',
            'current_approver_role' => 'pembina_hima',
                    ]);

        // Approval histories usulan
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan7->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Git wajib dikuasai developer.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(14),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan7->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Skill penting untuk mahasiswa.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(13),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan7->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Usulan disetujui.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(12),
        ]);

        $this->command->info('✅ Tahap Proposal: 2 kegiatan (draft, dikirim)');

        // === TAHAP PENDANAAN (2 kegiatan) ===

        // Pendanaan 1: Draft (Proposal approved, belum upload RAB)
        $kegiatan9 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Seminar Data Science & ML',
            'deskripsi_kegiatan' => 'Seminar tentang data science dan machine learning.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Seminar')->first()->id,
            'tempat_kegiatan' => 'Aula Gedung C',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Sponsor')->first()->id,
            'tanggal_mulai' => Carbon::now()->addDays(70),
            'tanggal_akhir' => Carbon::now()->addDays(70),
            'tahap' => 'pendanaan',
            'status' => 'draft',
            'current_approver_role' => null,
                    ]);

        // Histories: Usulan approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Data science topik yang bagus.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(24),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'ML sangat relevan saat ini.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(23),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Usulan disetujui.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(22),
        ]);

        // Histories: Proposal approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Proposal detail dan terstruktur.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(10),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Proposal bagus.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(9),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan9->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Proposal disetujui. Lanjut pendanaan.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(8),
        ]);

        $this->command->info('✅ Tahap Pendanaan: 1 kegiatan (draft)');

        // === TAHAP LAPORAN (2 kegiatan) ===

        // Laporan 1: Draft (Kegiatan sudah selesai, belum upload LPJ)
        $kegiatan11 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Pelatihan Database MySQL',
            'deskripsi_kegiatan' => 'Pelatihan pengelolaan database MySQL.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Pelatihan')->first()->id,
            'tempat_kegiatan' => 'Lab Komputer',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Hibah')->first()->id,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_akhir' => Carbon::now()->subDays(9),
            'tahap' => 'laporan',
            'status' => 'draft',
            'current_approver_role' => null,
                    ]);

        // Histories: Usulan approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Database skill fundamental.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(34),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'MySQL penting untuk backend.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(33),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Usulan disetujui.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(32),
        ]);

        // Histories: Proposal approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Proposal OK.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(20),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Proposal disetujui.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(19),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Proposal disetujui.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(18),
        ]);

        // Histories: Pendanaan approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'RAB disetujui.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(12),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'RAB OK.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(11),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan11->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'RAB disetujui.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(10),
        ]);

        // Laporan 2: Completed (LPJ approved, kegiatan selesai)
        $kegiatan12 = Kegiatan::create([
            'user_id' => $himaUser->id,
            'prodi_id' => $prodi->id,
            'nama_kegiatan' => 'Seminar Blockchain & Crypto',
            'deskripsi_kegiatan' => 'Seminar teknologi blockchain.',
            'jenis_kegiatan_id' => $jenisKegiatans->where('nama', 'Seminar')->first()->id,
            'tempat_kegiatan' => 'Aula',
            'jenis_pendanaan_id' => $jenisPendanaans->where('nama', 'Internal Kampus')->first()->id,
            'tanggal_mulai' => Carbon::now()->subDays(15),
            'tanggal_akhir' => Carbon::now()->subDays(15),
            'tahap' => 'laporan',
            'status' => 'disetujui',
            'current_approver_role' => null,
                    ]);

        // Histories: Usulan approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Blockchain topik yang menarik.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(39),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Teknologi masa depan.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(38),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Usulan disetujui.',
            'tahap' => 'usulan',
            'approved_at' => Carbon::now()->subDays(37),
        ]);

        // Histories: Proposal approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'Proposal bagus.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(25),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'Proposal disetujui.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(24),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'Proposal disetujui.',
            'tahap' => 'proposal',
            'approved_at' => Carbon::now()->subDays(23),
        ]);

        // Histories: Pendanaan approved
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'RAB disetujui.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(17),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'RAB OK.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(16),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'RAB disetujui.',
            'tahap' => 'pendanaan',
            'approved_at' => Carbon::now()->subDays(15),
        ]);

        // Histories: Laporan approved (SELESAI)
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $pembinaUser->id,
            'approver_role' => 'pembina_hima',
            'action' => 'disetujui',
            'comment' => 'LPJ lengkap dan sesuai.',
            'tahap' => 'laporan',
            'approved_at' => Carbon::now()->subDays(3),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $kaprodiUser->id,
            'approver_role' => 'kaprodi',
            'action' => 'disetujui',
            'comment' => 'LPJ disetujui.',
            'tahap' => 'laporan',
            'approved_at' => Carbon::now()->subDays(2),
        ]);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan12->id,
            'approver_user_id' => $wadekUser->id,
            'approver_role' => 'wadek_iii',
            'action' => 'disetujui',
            'comment' => 'LPJ disetujui. Kegiatan selesai dengan baik.',
            'tahap' => 'laporan',
            'approved_at' => Carbon::now()->subDays(1),
        ]);

        $this->command->info('✅ Tahap Laporan: 2 kegiatan (draft, disetujui/completed)');

        // Summary
        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('✅ Seeder berhasil dijalankan!');
        $this->command->info('==============================================');
        $this->command->info('Prodi: Sistem Informasi');
        $this->command->info('Total Kegiatan: 9');
        $this->command->info('Total Users: 4 (HIMA, Pembina, Kaprodi, Wadek III)');
        $this->command->info('');
        $this->command->info('📊 Kegiatan per Tahap:');
        $this->command->info('  - Usulan: 4 kegiatan');
        $this->command->info('    * Draft: 1');
        $this->command->info('    * Dikirim (menunggu Pembina): 1');
        $this->command->info('    * Dikirim (menunggu Kaprodi): 1');
        $this->command->info('    * Revisi (Pembina): 1');
        $this->command->info('');
        $this->command->info('  - Proposal: 2 kegiatan');
        $this->command->info('    * Draft (belum upload): 1');
        $this->command->info('    * Dikirim (menunggu approval): 1');
        $this->command->info('');
        $this->command->info('  - Pendanaan: 1 kegiatan');
        $this->command->info('    * Draft (belum upload RAB): 1');
        $this->command->info('');
        $this->command->info('  - Laporan: 2 kegiatan');
        $this->command->info('    * Draft (belum upload LPJ): 1');
        $this->command->info('    * Disetujui (COMPLETED): 1');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('  HIMA SI: hima.si@unja.ac.id / password');
        $this->command->info('  Pembina: pembina.si@unja.ac.id / password');
        $this->command->info('  Kaprodi: kaprodi.si@unja.ac.id / password');
        $this->command->info('  Wadek III: wadek.iii@unja.ac.id / password');
        $this->command->info('==============================================');
    }
}
