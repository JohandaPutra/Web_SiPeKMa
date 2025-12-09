<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\KegiatanFile;
use App\Models\ApprovalHistory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kegiatan_files')->truncate();
        DB::table('approval_histories')->truncate();
        DB::table('kegiatans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get users by role
        $himaIF = User::where('email', 'hima.if@example.com')->first();
        $himaSI = User::where('email', 'hima.si@example.com')->first();
        $himaTK = User::where('email', 'hima.tk@example.com')->first();

        $pembinaIF = User::where('email', 'pembina.if@example.com')->first();
        $pembinaSI = User::where('email', 'pembina.si@example.com')->first();
        $pembinaTK = User::where('email', 'pembina.tk@example.com')->first();

        $kaprodiIF = User::where('email', 'kaprodi.if@example.com')->first();
        $kaprodiSI = User::where('email', 'kaprodi.si@example.com')->first();
        $kaprodiTK = User::where('email', 'kaprodi.tk@example.com')->first();

        $wadek = User::where('email', 'wadek.iii@example.com')->first();

        // ==========================================
        // TAHAP USULAN - Berbagai Status
        // ==========================================

        // 1. Usulan Draft (belum submit)
        $kegiatan1 = $this->createKegiatan([
            'user_id' => $himaIF->id,
            'prodi_id' => $himaIF->prodi_id,
            'nama_kegiatan' => 'Seminar Nasional Teknologi Informasi 2025',
            'jenis_kegiatan' => 'seminar',
            'deskripsi_kegiatan' => 'Seminar nasional dengan tema "AI for Future" yang menghadirkan pembicara dari industri dan akademisi.',
            'tanggal_mulai' => Carbon::now()->addMonths(2),
            'tanggal_akhir' => Carbon::now()->addMonths(2)->addDays(2),
            'tempat_kegiatan' => 'Auditorium Kampus',
            'jenis_pendanaan' => 'internal',
            'tahap' => 'usulan',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);

        // 2. Usulan Submitted - Menunggu Pembina
        $kegiatan2 = $this->createKegiatan([
            'user_id' => $himaSI->id,
            'prodi_id' => $himaSI->prodi_id,
            'nama_kegiatan' => 'Workshop Database Management Systems',
            'jenis_kegiatan' => 'workshop',
            'deskripsi_kegiatan' => 'Workshop intensif tentang desain dan optimasi database menggunakan PostgreSQL dan MongoDB.',
            'tanggal_mulai' => Carbon::now()->addMonths(1)->addDays(15),
            'tanggal_akhir' => Carbon::now()->addMonths(1)->addDays(17),
            'tempat_kegiatan' => 'Lab Komputer 3',
            'jenis_pendanaan' => 'sponsor',
            'tahap' => 'usulan',
            'status' => 'submitted',
            'current_approver_role' => 'pembina_hima',
        ]);

        // 3. Usulan di Pembina - Sedang Review
        $kegiatan3 = $this->createKegiatan([
            'user_id' => $himaTK->id,
            'prodi_id' => $himaTK->prodi_id,
            'nama_kegiatan' => 'Kompetisi Robotika Tingkat Nasional',
            'jenis_kegiatan' => 'lomba',
            'deskripsi_kegiatan' => 'Kompetisi robotika line follower dan soccer robot untuk mahasiswa se-Indonesia.',
            'tanggal_mulai' => Carbon::now()->addMonths(3),
            'tanggal_akhir' => Carbon::now()->addMonths(3)->addDays(1),
            'tempat_kegiatan' => 'Gedung Olahraga Kampus',
            'jenis_pendanaan' => 'hibah',
            'tahap' => 'usulan',
            'status' => 'submitted',
            'current_approver_role' => 'pembina_hima',
        ]);

        // 4. Usulan Approved Pembina - Menunggu Kaprodi
        $kegiatan4 = $this->createKegiatan([
            'user_id' => $himaIF->id,
            'prodi_id' => $himaIF->prodi_id,
            'nama_kegiatan' => 'Pelatihan Mobile App Development dengan Flutter',
            'jenis_kegiatan' => 'pelatihan',
            'deskripsi_kegiatan' => 'Pelatihan pengembangan aplikasi mobile cross-platform menggunakan Flutter framework.',
            'tanggal_mulai' => Carbon::now()->addMonth(),
            'tanggal_akhir' => Carbon::now()->addMonth()->addDays(5),
            'tempat_kegiatan' => 'Lab Mobile Programming',
            'jenis_pendanaan' => 'internal',
            'tahap' => 'usulan',
            'status' => 'submitted',
            'current_approver_role' => 'kaprodi',
        ]);
        $this->addApproval($kegiatan4->id, $pembinaIF->id, 'approved', 'Usulan bagus dan sesuai kebutuhan mahasiswa.');

        // 5. Usulan Approved Kaprodi - Menunggu Wadek
        $kegiatan5 = $this->createKegiatan([
            'user_id' => $himaSI->id,
            'prodi_id' => $himaSI->prodi_id,
            'nama_kegiatan' => 'Studytour ke Perusahaan IT Startup',
            'jenis_kegiatan' => 'lainnya',
            'deskripsi_kegiatan' => 'Kunjungan industri ke berbagai perusahaan startup teknologi di Jakarta untuk mengenal ekosistem startup.',
            'tanggal_mulai' => Carbon::now()->addMonths(2)->addDays(10),
            'tanggal_akhir' => Carbon::now()->addMonths(2)->addDays(12),
            'tempat_kegiatan' => 'Jakarta',
            'jenis_pendanaan' => 'mandiri',
            'tahap' => 'usulan',
            'status' => 'submitted',
            'current_approver_role' => 'wadek_iii',
        ]);
        $this->addApproval($kegiatan5->id, $pembinaSI->id, 'approved', 'Kegiatan ini dapat memberi exposure industri kepada mahasiswa.');
        $this->addApproval($kegiatan5->id, $kaprodiSI->id, 'approved', 'Sangat mendukung, akan meningkatkan wawasan mahasiswa tentang dunia kerja.');

        // 6. Usulan Approved Wadek - Siap Upload Proposal
        $kegiatan6 = $this->createKegiatan([
            'user_id' => $himaTK->id,
            'prodi_id' => $himaTK->prodi_id,
            'nama_kegiatan' => 'Webinar Series: IoT & Smart Systems',
            'jenis_kegiatan' => 'seminar',
            'deskripsi_kegiatan' => 'Series webinar tentang Internet of Things dan sistem cerdas dengan pembicara praktisi industri.',
            'tanggal_mulai' => Carbon::now()->addDays(45),
            'tanggal_akhir' => Carbon::now()->addDays(47),
            'tempat_kegiatan' => 'Online via Zoom',
            'jenis_pendanaan' => 'sponsor',
            'tahap' => 'usulan',
            'status' => 'approved',
            'current_approver_role' => 'completed',
        ]);
        $this->addApproval($kegiatan6->id, $pembinaTK->id, 'approved', 'Topik yang sangat relevan dengan perkembangan teknologi saat ini.');
        $this->addApproval($kegiatan6->id, $kaprodiTK->id, 'approved', 'Disetujui, mohon koordinasi dengan prodi untuk narasumber.');
        $this->addApproval($kegiatan6->id, $wadek->id, 'approved', 'Disetujui. Silakan lanjutkan ke tahap proposal.');

        // 7. Usulan Revision
        $kegiatan7 = $this->createKegiatan([
            'user_id' => $himaIF->id,
            'prodi_id' => $himaIF->prodi_id,
            'nama_kegiatan' => 'Hackathon 24 Jam',
            'jenis_kegiatan' => 'lomba',
            'deskripsi_kegiatan' => 'Kompetisi pemrograman 24 jam non-stop dengan tema Smart City Solutions.',
            'tanggal_mulai' => Carbon::now()->addDays(60),
            'tanggal_akhir' => Carbon::now()->addDays(61),
            'tempat_kegiatan' => 'Lab Komputer 1 & 2',
            'jenis_pendanaan' => 'sponsor',
            'tahap' => 'usulan',
            'status' => 'revision',
            'current_approver_role' => 'pembina_hima',
        ]);
        $this->addApproval($kegiatan7->id, $pembinaIF->id, 'revision', 'Mohon tambahkan detail anggaran akomodasi peserta dan jadwal yang lebih spesifik.');

        // 8. Usulan Rejected
        $kegiatan8 = $this->createKegiatan([
            'user_id' => $himaSI->id,
            'prodi_id' => $himaSI->prodi_id,
            'nama_kegiatan' => 'Trip Wisata ke Bali',
            'jenis_kegiatan' => 'lainnya',
            'deskripsi_kegiatan' => 'Perjalanan wisata mahasiswa ke Bali untuk refreshing.',
            'tanggal_mulai' => Carbon::now()->addMonths(1),
            'tanggal_akhir' => Carbon::now()->addMonths(1)->addDays(4),
            'tempat_kegiatan' => 'Bali',
            'jenis_pendanaan' => 'mandiri',
            'tahap' => 'usulan',
            'status' => 'rejected',
            'current_approver_role' => null,
        ]);
        $this->addApproval($kegiatan8->id, $pembinaSI->id, 'rejected', 'Usulan tidak memiliki nilai akademis dan tidak sesuai dengan tujuan organisasi kemahasiswaan.');

        // ==========================================
        // TAHAP PROPOSAL - Draft Siap Submit
        // ==========================================

        // 9. Proposal Draft - Usulan sudah approved, sudah pindah ke tahap proposal
        $kegiatan9 = $this->createKegiatan([
            'user_id' => $himaTK->id,
            'prodi_id' => $himaTK->prodi_id,
            'nama_kegiatan' => 'Workshop Cyber Security Fundamentals',
            'jenis_kegiatan' => 'workshop',
            'deskripsi_kegiatan' => 'Workshop tentang dasar-dasar keamanan siber, ethical hacking, dan penetration testing.',
            'tanggal_mulai' => Carbon::now()->addMonths(2)->addDays(5),
            'tanggal_akhir' => Carbon::now()->addMonths(2)->addDays(7),
            'tempat_kegiatan' => 'Lab Jaringan Komputer',
            'jenis_pendanaan' => 'internal',
            'tahap' => 'proposal',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);
        // Approval history untuk tahap usulan (sudah selesai)
        $this->addApprovalWithTahap($kegiatan9->id, $pembinaTK->id, 'approved', 'Topik cyber security sangat penting untuk mahasiswa.', 'usulan');
        $this->addApprovalWithTahap($kegiatan9->id, $kaprodiTK->id, 'approved', 'Disetujui, mohon koordinasi dengan lab untuk peralatan.', 'usulan');
        $this->addApprovalWithTahap($kegiatan9->id, $wadek->id, 'approved', 'Disetujui. Usulan diterima, silakan lanjut ke proposal.', 'usulan');
        // Simulasi file proposal sudah diupload
        $this->addFile($kegiatan9->id, 'proposal', 'Proposal_Workshop_CyberSecurity.pdf', 1856432);

        // ==========================================
        // TAHAP PENDANAAN - Draft Siap Submit
        // ==========================================

        // 10. Pendanaan Draft - Proposal sudah approved, sudah pindah ke tahap pendanaan
        $kegiatan10 = $this->createKegiatan([
            'user_id' => $himaIF->id,
            'prodi_id' => $himaIF->prodi_id,
            'nama_kegiatan' => 'Tech Talk: Career Path in IT Industry',
            'jenis_kegiatan' => 'seminar',
            'deskripsi_kegiatan' => 'Seminar tentang berbagai jalur karir di industri IT dengan pembicara dari berbagai perusahaan.',
            'tanggal_mulai' => Carbon::now()->addDays(30),
            'tanggal_akhir' => Carbon::now()->addDays(30),
            'tempat_kegiatan' => 'Aula Prodi Informatika',
            'jenis_pendanaan' => 'internal',
            'tahap' => 'pendanaan',
            'status' => 'draft',
            'current_approver_role' => null,
        ]);
        // Approval history untuk tahap usulan
        $this->addApprovalWithTahap($kegiatan10->id, $pembinaIF->id, 'approved', 'Kegiatan sangat bermanfaat untuk mahasiswa.', 'usulan');
        $this->addApprovalWithTahap($kegiatan10->id, $kaprodiIF->id, 'approved', 'Mendukung penuh kegiatan ini.', 'usulan');
        $this->addApprovalWithTahap($kegiatan10->id, $wadek->id, 'approved', 'Disetujui untuk tahap usulan.', 'usulan');
        // File proposal yang sudah diupload
        $this->addFile($kegiatan10->id, 'proposal', 'Proposal_Tech_Talk_Career_Path.pdf', 2458624);
        // Approval history untuk tahap proposal
        $this->addApprovalWithTahap($kegiatan10->id, $pembinaIF->id, 'approved', 'Proposal lengkap dan detail.', 'proposal');
        $this->addApprovalWithTahap($kegiatan10->id, $kaprodiIF->id, 'approved', 'Proposal sangat baik.', 'proposal');
        $this->addApprovalWithTahap($kegiatan10->id, $wadek->id, 'approved', 'Disetujui untuk proposal. Lanjut ke pendanaan.', 'proposal');
        // Simulasi file RAB sudah diupload dengan anggaran
        $this->addFile($kegiatan10->id, 'rab', 'RAB_Tech_Talk_Career_Path.pdf', 1345678);
        Kegiatan::where('id', $kegiatan10->id)->update(['total_anggaran' => 15000000]);

        // ==========================================
        // TAHAP LAPORAN - Draft Siap Submit
        // ==========================================

        // 11. Laporan Draft - Pendanaan sudah approved, kegiatan sudah selesai
        $kegiatan11 = $this->createKegiatan([
            'user_id' => $himaSI->id,
            'prodi_id' => $himaSI->prodi_id,
            'nama_kegiatan' => 'Pelatihan Database Management',
            'jenis_kegiatan' => 'workshop',
            'deskripsi_kegiatan' => 'Pelatihan manajemen database untuk mahasiswa Sistem Informasi.',
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_akhir' => Carbon::now()->subDays(9),
            'tempat_kegiatan' => 'Lab Database Prodi SI',
            'jenis_pendanaan' => 'internal',
            'tahap' => 'laporan',
            'status' => 'draft',
            'total_anggaran' => 5000000,
            'current_approver_role' => null,
        ]);
        // Historical approvals (all previous stages approved)
        $this->addApprovalWithTahap($kegiatan11->id, $pembinaSI->id, 'approved', 'Usulan disetujui.', 'usulan');
        $this->addApprovalWithTahap($kegiatan11->id, $kaprodiSI->id, 'approved', 'Usulan disetujui.', 'usulan');
        $this->addApprovalWithTahap($kegiatan11->id, $wadek->id, 'approved', 'Usulan disetujui, lanjut proposal.', 'usulan');
        $this->addFile($kegiatan11->id, 'proposal', 'Proposal_Pelatihan_Database.pdf', 1856432);
        $this->addApprovalWithTahap($kegiatan11->id, $pembinaSI->id, 'approved', 'Proposal disetujui.', 'proposal');
        $this->addApprovalWithTahap($kegiatan11->id, $kaprodiSI->id, 'approved', 'Proposal disetujui.', 'proposal');
        $this->addApprovalWithTahap($kegiatan11->id, $wadek->id, 'approved', 'Proposal disetujui, lanjut pendanaan.', 'proposal');
        $this->addFile($kegiatan11->id, 'rab', 'RAB_Pelatihan_Database.pdf', 1234567);
        $this->addApprovalWithTahap($kegiatan11->id, $pembinaSI->id, 'approved', 'RAB disetujui.', 'pendanaan');
        $this->addApprovalWithTahap($kegiatan11->id, $kaprodiSI->id, 'approved', 'RAB disetujui.', 'pendanaan');
        $this->addApprovalWithTahap($kegiatan11->id, $wadek->id, 'approved', 'RAB disetujui, silakan upload LPJ setelah kegiatan selesai.', 'pendanaan');

        $this->command->info('✅ Seeder berhasil: 11 kegiatan dengan berbagai status');
        $this->command->info('   - 6 usulan (draft, submitted, 2x di pembina, di kaprodi, di wadek)');
        $this->command->info('   - 2 usulan selesai (revision, rejected)');
        $this->command->info('   - 1 proposal draft (usulan approved, file proposal uploaded)');
        $this->command->info('   - 1 pendanaan draft (proposal approved, file RAB uploaded)');
        $this->command->info('   - 1 laporan draft (pendanaan approved, kegiatan selesai, siap upload LPJ)');
    }

    private function createKegiatan(array $data): Kegiatan
    {
        return Kegiatan::create($data);
    }

    private function addApproval(int $kegiatanId, int $userId, string $action, string $comment): void
    {
        $kegiatan = Kegiatan::find($kegiatanId);
        $user = User::find($userId);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanId,
            'approver_user_id' => $userId,
            'approver_role' => $user->role->name,
            'tahap' => $kegiatan->tahap,
            'action' => $action,
            'comment' => $comment,
            'approved_at' => Carbon::now()->subDays(rand(1, 7)),
        ]);
    }

    private function addApprovalWithTahap(int $kegiatanId, int $userId, string $action, string $comment, string $tahap): void
    {
        $user = User::find($userId);

        ApprovalHistory::create([
            'kegiatan_id' => $kegiatanId,
            'approver_user_id' => $userId,
            'approver_role' => $user->role->name,
            'tahap' => $tahap,
            'action' => $action,
            'comment' => $comment,
            'approved_at' => Carbon::now()->subDays(rand(1, 7)),
        ]);
    }

    private function addFile(int $kegiatanId, string $fileType, string $fileName, int $fileSize): void
    {
        KegiatanFile::create([
            'kegiatan_id' => $kegiatanId,
            'file_name' => $fileName,
            'file_path' => 'kegiatan_files/' . $fileType . '/' . $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'uploaded_by' => Kegiatan::find($kegiatanId)->user_id,
            'uploaded_at' => Carbon::now()->subDays(rand(1, 5)),
        ]);
    }
}
