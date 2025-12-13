<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanKegiatan extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'nama_kegiatan',
    'deskripsi_kegiatan',
    'jenis_kegiatan',
    'tempat_kegiatan',
    'jenis_pendanaan',
    'tanggal_mulai',
    'tanggal_akhir',
    'status_kegiatan',
  ];

  protected $casts = [
    'tanggal_mulai' => 'date',
    'tanggal_akhir' => 'date',
  ];

  /**
   * Get the user that owns the usulan kegiatan.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get status badge color
   */
  public function getStatusBadgeAttribute(): string
  {
    return match ($this->status_kegiatan) {
      'draft' => 'secondary',
      'dikirim' => 'info',
      'review' => 'warning',
      'disetujui' => 'success',
      'ditolak' => 'danger',
      default => 'secondary'
    };
  }

  /**
   * Get status label
   */
  public function getStatusLabelAttribute(): string
  {
    return match ($this->status_kegiatan) {
      'draft' => 'Draft',
      'dikirim' => 'Diajukan',
      'review' => 'Review',
      'disetujui' => 'Disetujui',
      'ditolak' => 'Ditolak',
      default => 'Draft'
    };
  }
}
