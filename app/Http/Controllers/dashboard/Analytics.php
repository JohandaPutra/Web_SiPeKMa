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
    if ($user->isHima()) {
      // Hima hanya melihat usulan miliknya
      $totalUsulan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'usulan')
        ->count();
      $proposalDisetujui = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'proposal')
        ->count();
      $pendanaan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'pendanaan')
        ->count();
      $laporan = Kegiatan::where('user_id', $user->id)
        ->where('tahap', 'laporan')
        ->count();
    } elseif ($user->role->name === 'pembina_hima') {
      // Pembina melihat usulan yang sudah disubmit di prodinya (tidak termasuk draft)
      $totalUsulan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'usulan')
        ->whereIn('status', ['submitted', 'revision'])
        ->count();
      $proposalDisetujui = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'proposal')
        ->count();
      $pendanaan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'pendanaan')
        ->count();
      $laporan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'laporan')
        ->count();
    } elseif ($user->role->name === 'kaprodi') {
      // Kaprodi melihat usulan yang sudah disetujui pembina di prodinya
      $totalUsulan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'usulan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'pembina_hima')->where('status', 'approved');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'proposal')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'pembina_hima')->where('status', 'approved');
        })
        ->count();
      $pendanaan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'pendanaan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('status', 'approved');
        })
        ->count();
      $laporan = Kegiatan::where('prodi_id', $user->prodi_id)
        ->where('tahap', 'laporan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('status', 'approved');
        })
        ->count();
    } else {
      // Wadek melihat semua usulan yang sudah disetujui kaprodi
      $totalUsulan = Kegiatan::where('tahap', 'usulan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('status', 'approved');
        })
        ->count();
      $proposalDisetujui = Kegiatan::where('tahap', 'proposal')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'kaprodi')->where('status', 'approved');
        })
        ->count();
      $pendanaan = Kegiatan::where('tahap', 'pendanaan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'wadek_iii')->where('status', 'approved');
        })
        ->count();
      $laporan = Kegiatan::where('tahap', 'laporan')
        ->whereHas('approvalHistories', function($q) {
          $q->where('approver_role', 'wadek_iii')->where('status', 'approved');
        })
        ->count();
    }
    
    return view('content.dashboard.dashboards-analytics', compact(
      'totalUsulan',
      'proposalDisetujui',
      'pendanaan',
      'laporan'
    ));
  }
}
