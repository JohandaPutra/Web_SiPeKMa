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
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('approver_user_id')->constrained('users')->onDelete('cascade');
            $table->string('approver_role'); // pembina_hima, kaprodi, wadek_iii
            $table->enum('tahap', ['usulan', 'proposal', 'pendanaan', 'laporan']);
            $table->enum('action', ['disetujui', 'revisi', 'ditolak']);
            $table->text('comment')->nullable(); // Komentar untuk revisi
            $table->timestamp('approved_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
