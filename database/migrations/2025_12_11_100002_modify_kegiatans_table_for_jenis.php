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
        Schema::table('kegiatans', function (Blueprint $table) {
            // Drop old enum columns
            $table->dropColumn(['jenis_kegiatan', 'jenis_pendanaan']);
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            // Add foreign keys to new jenis tables
            $table->foreignId('jenis_kegiatan_id')->after('deskripsi_kegiatan')->constrained('jenis_kegiatans')->onDelete('restrict');
            $table->foreignId('jenis_pendanaan_id')->after('tanggal_akhir')->constrained('jenis_pendanaans')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropForeign(['jenis_kegiatan_id']);
            $table->dropForeign(['jenis_pendanaan_id']);
            $table->dropColumn(['jenis_kegiatan_id', 'jenis_pendanaan_id']);
        });

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->enum('jenis_kegiatan', ['seminar', 'workshop', 'pelatihan', 'lomba', 'lainnya'])->after('deskripsi_kegiatan');
            $table->enum('jenis_pendanaan', ['mandiri', 'sponsor', 'hibah', 'internal'])->after('tanggal_akhir');
        });
    }
};
