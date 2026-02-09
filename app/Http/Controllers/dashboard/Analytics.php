<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Analytics extends Controller
{
  public function index()
  {
    $user = Auth::user();

    // Hitung data berdasarkan role
    if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
      // Super Admin dan Admin melihat SEMUA kegiatan dari SEMUA prodi (exclude rejected)
      $totalUsulan = Kegiatan::where('tahap', 'usulan')
        ->where('status', '!=', 'ditolak')
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('tahap', 'proposal')
        ->where('status', '!=', 'ditolak')
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $pendanaan = Kegiatan::where('tahap', 'pendanaan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $laporan = Kegiatan::where('tahap', 'laporan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
    } elseif ($user->isHima()) {
      // Hima hanya melihat usulan miliknya (exclude rejected)
      $totalUsulan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'usulan')
        ->where('status', '!=', 'ditolak')
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'proposal')
        ->where('status', '!=', 'ditolak')
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $pendanaan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'pendanaan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $laporan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'laporan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
    } elseif ($user->role->name === 'pembina_hima') {
      // Pembina melihat usulan yang sudah disubmit di prodinya (exclude draft dan ditolak)
      $totalUsulan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'usulan')
        ->whereIn('status', ['dikirim', 'revisi'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'proposal')
        ->where('status', '!=', 'ditolak')
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $pendanaan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'pendanaan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $laporan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'laporan')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
    } elseif ($user->role->name === 'kaprodi') {
      // Kaprodi melihat usulan yang sudah disetujui pembina di prodinya (exclude rejected)
      $totalUsulan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'usulan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'pembina_hima')->where('tahap', 'usulan')->where('action', 'disetujui');
        })
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'proposal')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'pembina_hima')->where('tahap', 'proposal')->where('action', 'disetujui');
        })
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $pendanaan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'pendanaan')
        ->whereIn('current_approver_role', ['kaprodi', 'wadek_iii'])
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $laporan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'laporan')
        ->whereIn('current_approver_role', ['kaprodi', 'wadek_iii'])
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
    } else {
      // Wadek melihat semua usulan yang sudah disetujui kaprodi (exclude rejected)
      $totalUsulan = Kegiatan::where('tahap', 'usulan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('tahap', 'usulan')->where('action', 'disetujui');
        })
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('tahap', 'proposal')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('tahap', 'proposal')->where('action', 'disetujui');
        })
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $pendanaan = Kegiatan::where('tahap', 'pendanaan')
        ->where('current_approver_role', 'wadek_iii')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
      $laporan = Kegiatan::where('tahap', 'laporan')
        ->where('current_approver_role', 'wadek_iii')
        ->whereNotIn('status', ['ditolak', 'disetujui'])
        ->whereDoesntHave('approvalHistories', function($q) {
          $q->where('action', 'ditolak');
        })
        ->count();
    }

    return view('dashboard.index', compact(
      'totalUsulan',
      'proposalDisetujui',
      'pendanaan',
      'laporan'
    ));
  }
}
