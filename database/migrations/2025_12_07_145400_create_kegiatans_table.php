<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Hima yang membuat
            $table->foreignId('prodi_id')->constrained()->onDelete('cascade');

            // Informasi Kegiatan
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->enum('jenis_kegiatan', ['seminar', 'workshop', 'pelatihan', 'lomba', 'lainnya']);
            $table->string('tempat_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->enum('jenis_pendanaan', ['mandiri', 'sponsor', 'hibah', 'internal']);

            // Workflow Status
            $table->enum('tahap', ['usulan', 'proposal', 'pendanaan', 'laporan'])->default('usulan');
            $table->enum('status', ['draft', 'dikirim', 'disetujui', 'revisi', 'ditolak'])->default('draft');
            $table->enum('current_approver_role', ['pembina_hima', 'kaprodi', 'wadek_iii', 'completed'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
