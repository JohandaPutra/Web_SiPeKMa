<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'role_id',
    'prodi_id'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  /**
   * Get the role of the user
   */
  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }

  /**
   * Get the prodi of the user
   */
  public function prodi(): BelongsTo
  {
    return $this->belongsTo(Prodi::class);
  }

  /**
   * Get kegiatans created by this user
   */
  public function kegiatans(): HasMany
  {
    return $this->hasMany(Kegiatan::class);
  }

  /**
   * Get approval histories by this user
   */
  public function approvalHistories(): HasMany
  {
    return $this->hasMany(ApprovalHistory::class, 'approver_user_id');
  }

  /**
   * Check if user has specific role
   */
  public function hasRole(string $roleName): bool
  {
    return $this->role && $this->role->name === $roleName;
  }

  /**
   * Check if user is Hima
   */
  public function isHima(): bool
  {
    return $this->hasRole('hima');
  }

  /**
   * Check if user is Pembina Hima
   */
  public function isPembina(): bool
  {
    return $this->hasRole('pembina_hima');
  }

  /**
   * Check if user is Kaprodi
   */
  public function isKaprodi(): bool
  {
    return $this->hasRole('kaprodi');
  }

  /**
   * Check if user is Wadek III
   */
  public function isWadek(): bool
  {
    return $this->hasRole('wadek_iii');
  }

  /**
   * Check if user is Super Admin
   */
  public function isSuperAdmin(): bool
  {
    return $this->hasRole('admin');
  }

  /**
   * Check if user is admin (Super Admin or Wadek III)
   */
  public function isAdmin(): bool
  {
    return $this->isSuperAdmin() || $this->isWadek();
  }

  /**
   * Check if user has management access (Super Admin or Wadek III)
   */
  public function canManage(): bool
  {
    return $this->isSuperAdmin() || $this->isWadek();
  }
}
