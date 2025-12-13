<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPendanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get kegiatans with this jenis
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'jenis_pendanaan_id');
    }

    /**
     * Scope: only active jenis
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
