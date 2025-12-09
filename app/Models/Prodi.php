<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
    ];

    /**
     * Get users in this prodi
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get kegiatans in this prodi
     */
    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
