<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'approver_user_id',
        'approver_role',
        'tahap',
        'action',
        'comment',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the kegiatan
     */
    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * Get the approver user
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    /**
     * Get action badge color
     */
    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'disetujui' => 'success',
            'revisi' => 'warning',
            'ditolak' => 'danger',
            default => 'secondary'
        };
    }
}
