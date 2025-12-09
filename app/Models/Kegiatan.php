<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'jenis_kegiatan',
        'tempat_kegiatan',
        'tanggal_mulai',
        'tanggal_akhir',
        'jenis_pendanaan',
        'total_anggaran',
        'tahap',
        'status',
        'current_approver_role',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'total_anggaran' => 'decimal:2',
    ];

    /**
     * Get the user (Hima) that created this kegiatan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prodi of this kegiatan
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    /**
     * Get approval histories for this kegiatan
     */
    public function approvalHistories(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    /**
     * Get files for this kegiatan
     */
    public function files(): HasMany
    {
        return $this->hasMany(KegiatanFile::class);
    }

    /**
     * Get file for specific tahap
     */
    public function getFileByTahap(string $tahap): ?KegiatanFile
    {
        return $this->files()->where('tahap', $tahap)->latest('uploaded_at')->first();
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'submitted' => 'info',
            'revision' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get tahap badge color
     */
    public function getTahapBadgeAttribute(): string
    {
        return match ($this->tahap) {
            'usulan' => 'primary',
            'proposal' => 'info',
            'pendanaan' => 'warning',
            'laporan' => 'success',
            default => 'secondary'
        };
    }
}
