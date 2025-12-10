<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanFile;
use App\Models\ApprovalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Display riwayat kegiatan (all stages).
     */
    public function riwayat()
    {
        $user = Auth::user();

        // Base query dengan eager loading
        $query = Kegiatan::with(['user', 'prodi', 'approvalHistories.approver', 'files']);

        // Filter berdasarkan role
        if ($user->isHima() || $user->isPembina() || $user->isKaprodi()) {
            // Hima, Pembina, dan Kaprodi melihat semua kegiatan di prodi mereka
            $query->where('prodi_id', $user->prodi_id);
        } elseif ($user->isWadek()) {
            // Wadek III melihat semua kegiatan dari semua prodi
            // Tidak ada filter prodi_id
        } else {
            $kegiatans = collect();
            $prodis = collect();
            return view('kegiatan.riwayat.index', compact('kegiatans', 'prodis'));
        }

        // Filter berdasarkan request
        if (request('search')) {
            $query->where('nama_kegiatan', 'like', '%' . request('search') . '%');
        }

        if (request('prodi_id')) {
            $query->where('prodi_id', request('prodi_id'));
        }

        if (request('tahap')) {
            $query->where('tahap', request('tahap'));
        }

        // Urutkan berdasarkan terbaru
        $query->latest();

        // Handle pagination
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $kegiatans = $query->get();
        } else {
            $kegiatans = $query->paginate((int)$perPage)->appends(request()->query());
        }

        // Ambil list prodi untuk filter (hanya untuk Wadek III)
        $prodis = collect();
        if ($user->isWadek()) {
            $prodis = \App\Models\Prodi::orderBy('nama_prodi')->get();
        }

        return view('kegiatan.riwayat.index', compact('kegiatans', 'prodis'));
    }

    /**
     * Display detail riwayat kegiatan.
     */
    public function showRiwayat(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Validasi akses - Hima, Pembina, Kaprodi hanya bisa lihat kegiatan di prodi mereka
        if ($user->isHima() || $user->isPembina() || $user->isKaprodi()) {
            if ($kegiatan->prodi_id !== $user->prodi_id) {
                abort(403, 'Unauthorized');
            }
        }
        // Wadek III bisa lihat semua

        // Load semua relasi
        $kegiatan->load(['user', 'prodi', 'approvalHistories.approver', 'files']);

        // Group approval histories by tahap
        $approvalsByTahap = $kegiatan->approvalHistories->groupBy('tahap');

        // Get files by tahap (gunakan field 'tahap' bukan 'file_type')
        $proposalFile = $kegiatan->files->where('tahap', 'proposal')->first();
        $rabFile = $kegiatan->files->where('tahap', 'pendanaan')->first();
        $laporanFile = $kegiatan->files->where('tahap', 'laporan')->first();

        return view('kegiatan.riwayat.show', compact('kegiatan', 'approvalsByTahap', 'proposalFile', 'rabFile', 'laporanFile'));
    }

    /**
     * Display a listing of the resource (Usulan only).
     */
    public function index()
    {
        $user = Auth::user();

        // Base query - exclude rejected kegiatan
        $query = Kegiatan::with(['user', 'prodi', 'approvalHistories.approver'])
            ->where('tahap', 'usulan')
            ->where('status', '!=', 'rejected')
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'rejected');
            });

        // Filter kegiatan tahap usulan saja
        if ($user->isHima()) {
            // Hima melihat usulan yang dia buat (semua status)
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat usulan prodi mereka yang sudah disubmit (tidak termasuk draft)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('status', ['submitted', 'revision']);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat usulan prodi mereka yang sudah disetujui pembina
            $query->where('prodi_id', $user->prodi_id)
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'pembina_hima')
                      ->where('tahap', 'usulan')
                      ->where('action', 'approved');
                });
        } elseif ($user->isWadek()) {
            // Wadek melihat usulan yang sudah disetujui kaprodi
            $query->whereHas('approvalHistories', function($q) {
                $q->where('approver_role', 'kaprodi')
                  ->where('tahap', 'usulan')
                  ->where('action', 'approved');
            });
        } else {
            $kegiatans = collect();
            return view('kegiatan.index', compact('kegiatans'));
        }

        // Apply ordering
        $query->latest();

        // Handle pagination
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $kegiatans = $query->get();
        } else {
            $kegiatans = $query->paginate((int)$perPage)->appends(request()->query());
        }

        return view('kegiatan.index', compact('kegiatans'));
    }

    /**
     * Display a listing of proposals only.
     */
    public function indexProposal()
    {
        $user = Auth::user();

        // Base query - exclude rejected kegiatan
        $query = Kegiatan::with(['user', 'prodi', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'proposal')
            ->where('status', '!=', 'rejected')
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'rejected');
            });

        // Filter kegiatan tahap proposal berdasarkan role dan visibility
        if ($user->isHima()) {
            // Hima melihat semua proposal yang dia buat
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat semua proposal prodi mereka (termasuk draft untuk progress)
            $query->where('prodi_id', $user->prodi_id);
        } elseif ($user->isKaprodi()) {
            // Kaprodi hanya melihat proposal yang sudah disetujui Pembina
            $query->where('prodi_id', $user->prodi_id)
                ->where(function($query) {
                    $query->where('current_approver_role', 'kaprodi')
                          ->orWhere('current_approver_role', 'wadek_iii')
                          ->orWhere('current_approver_role', 'completed');
                });
        } elseif ($user->isWadek()) {
            // Wadek hanya melihat proposal yang sudah disetujui Kaprodi
            $query->where(function($query) {
                $query->where('current_approver_role', 'wadek_iii')
                      ->orWhere('current_approver_role', 'completed');
            });
        } else {
            $kegiatans = collect();
            return view('kegiatan.proposal.index', compact('kegiatans'));
        }

        // Apply ordering
        $query->latest();

        // Handle pagination
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $kegiatans = $query->get();
        } else {
            $kegiatans = $query->paginate((int)$perPage)->appends(request()->query());
        }

        return view('kegiatan.proposal.index', compact('kegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya Hima yang bisa create
        if (!Auth::user()->isHima()) {
            abort(403, 'Hanya Hima yang dapat membuat usulan kegiatan.');
        }

        return view('kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isHima()) {
            return redirect()->back()->with('error', 'Hanya Hima yang dapat membuat usulan kegiatan.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|in:seminar,workshop,pelatihan,lomba,lainnya',
            'tempat_kegiatan' => 'required|string|max:255',
            'jenis_pendanaan' => 'required|in:mandiri,sponsor,hibah,internal',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            Kegiatan::create([
                'user_id' => $user->id,
                'prodi_id' => $user->prodi_id,
                'nama_kegiatan' => $request->nama_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'jenis_pendanaan' => $request->jenis_pendanaan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'tahap' => 'usulan',
                'status' => 'draft',
                'current_approver_role' => null,
            ]);

            return redirect()->route('kegiatan.index')
                ->with('success', 'Usulan kegiatan berhasil dibuat. Silakan submit untuk proses persetujuan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Check permission
        if ($user->isHima() && $kegiatan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        if (($user->isPembina() || $user->isKaprodi()) && $kegiatan->prodi_id !== $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        $kegiatan->load(['user', 'prodi', 'approvalHistories.approver.role']);

        return view('kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Hanya Hima pemilik yang bisa edit, dan hanya saat draft atau revision
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah dalam proses persetujuan.');
        }

        return view('kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah dalam proses persetujuan.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|in:seminar,workshop,pelatihan,lomba,lainnya',
            'tempat_kegiatan' => 'required|string|max:255',
            'jenis_pendanaan' => 'required|in:mandiri,sponsor,hibah,internal',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            $kegiatan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'jenis_pendanaan' => $request->jenis_pendanaan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]);

            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('success', 'Usulan kegiatan berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Hanya Hima pemilik yang bisa delete, dan hanya saat draft
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus kegiatan ini.');
        }

        if ($kegiatan->status !== 'draft') {
            return redirect()->back()->with('error', 'Kegiatan tidak dapat dihapus karena sudah dalam proses persetujuan.');
        }

        try {
            $kegiatan->delete();
            return redirect()->route('kegiatan.index')
                ->with('success', 'Usulan kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Submit kegiatan untuk proses approval
     */
    public function submit(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Kegiatan sudah disubmit.');
        }

        // Jika status revision, current_approver_role sudah di-set (tetap ke yang request revision)
        // Jika draft baru, mulai dari pembina_hima
        $updateData = ['status' => 'submitted'];

        if ($kegiatan->status === 'draft') {
            // Submit pertama kali, mulai dari pembina
            $updateData['current_approver_role'] = 'pembina_hima';
            $message = 'Usulan kegiatan berhasil disubmit. Menunggu persetujuan Pembina Hima.';
        } else {
            // Re-submit setelah revisi, kembali ke approver yang meminta revisi
            $approverName = match($kegiatan->current_approver_role) {
                'pembina_hima' => 'Pembina Hima',
                'kaprodi' => 'Kaprodi',
                'wadek_iii' => 'Wadek III',
                default => 'Approver',
            };
            $message = "Usulan kegiatan berhasil disubmit ulang. Menunggu persetujuan {$approverName}.";
        }

        $kegiatan->update($updateData);

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', $message);
    }

    /**
     * Approve kegiatan (untuk Pembina, Kaprodi, Wadek)
     */
    public function approve(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Check if user has permission to approve
        if ($kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menyetujui kegiatan ini saat ini.');
        }

        // Check prodi access (except Wadek)
        if (!$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $userRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'approved',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Determine next approver
            $nextApprover = match($userRole) {
                'pembina_hima' => 'kaprodi',
                'kaprodi' => 'wadek_iii',
                'wadek_iii' => 'completed',
                default => null,
            };

            // Update kegiatan status
            if ($nextApprover === 'completed') {
                // Wadek III approve = approval selesai, LANGSUNG pindah ke tahap berikutnya
                $currentTahap = $kegiatan->tahap;

                $nextTahap = match($currentTahap) {
                    'usulan' => 'proposal',
                    'proposal' => 'pendanaan',
                    'pendanaan' => 'laporan',
                    'laporan' => 'laporan', // Tahap terakhir tetap di laporan
                    default => $currentTahap,
                };

                // Status: 'approved' jika laporan selesai, 'draft' untuk tahap lainnya pindah
                $newStatus = ($currentTahap === 'laporan') ? 'approved' : 'draft';
                
                // current_approver_role: 'completed' jika laporan selesai, null untuk tahap lainnya
                $newApproverRole = ($currentTahap === 'laporan') ? 'completed' : null;

                $kegiatan->update([
                    'tahap' => $nextTahap,
                    'status' => $newStatus,
                    'current_approver_role' => $newApproverRole,
                ]);

                $tahapName = match($currentTahap) {
                    'usulan' => 'Usulan',
                    'proposal' => 'Proposal',
                    'pendanaan' => 'Pendanaan',
                    'laporan' => 'Laporan',
                    default => 'Kegiatan',
                };

                $nextTahapName = match($nextTahap) {
                    'proposal' => 'Proposal',
                    'pendanaan' => 'Pendanaan',
                    'laporan' => 'Laporan',
                    default => '',
                };

                if ($currentTahap === 'laporan') {
                    $message = "Kegiatan telah selesai dan disetujui sepenuhnya!";
                } else {
                    $message = "{$tahapName} telah disetujui! Kegiatan otomatis pindah ke tahap {$nextTahapName}. Silakan lengkapi dokumen tahap {$nextTahapName}.";
                }
            } else {
                $kegiatan->update([
                    'current_approver_role' => $nextApprover,
                ]);
                $nextApproverName = match($nextApprover) {
                    'kaprodi' => 'Kaprodi',
                    'wadek_iii' => 'Wadek III',
                    default => 'Unknown',
                };

                $tahapName = match($kegiatan->tahap) {
                    'usulan' => 'Usulan',
                    'proposal' => 'Proposal',
                    'pendanaan' => 'Pendanaan',
                    'laporan' => 'Laporan',
                    default => 'Kegiatan',
                };

                $message = "{$tahapName} berhasil disetujui. Menunggu persetujuan {$nextApproverName}.";
            }

            // Redirect berdasarkan tahap - kembali ke index untuk Wadek, show untuk approver lain
            if ($userRole === 'wadek_iii') {
                // Wadek III kembali ke index tahap yang baru saja disetujui (SEBELUM pindah tahap)
                $redirectTahap = match($currentTahap ?? $kegiatan->tahap) {
                    'usulan' => 'kegiatan.index',
                    'proposal' => 'kegiatan.proposal.index',
                    'pendanaan' => 'kegiatan.pendanaan.index',
                    'laporan' => 'kegiatan.index',
                    default => 'kegiatan.index',
                };
                return redirect()->route($redirectTahap)->with('success', $message);
            } else {
                // Pembina/Kaprodi/Wadek tetap ke show page sesuai tahap
                $redirectRoute = match($kegiatan->tahap) {
                    'usulan' => 'kegiatan.show',
                    'proposal' => 'kegiatan.proposal.show',
                    'pendanaan' => 'kegiatan.pendanaan.show',
                    'laporan' => 'kegiatan.laporan.show',
                    default => 'kegiatan.show',
                };
                return redirect()->route($redirectRoute, $kegiatan)->with('success', $message);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * Request revision (untuk Pembina, Kaprodi, Wadek)
     */
    public function revision(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        $request->validate([
            'comment' => 'required|string',
        ]);

        // Check if user has permission
        if ($kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk meminta revisi saat ini.');
        }

        // Check prodi access (except Wadek)
        if (!$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $userRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'revision',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Update kegiatan status - kembali ke Hima untuk revisi
            // current_approver_role tetap sama (akan kembali ke role yang request revision)
            $kegiatan->update([
                'status' => 'revision',
            ]);

            // Redirect ke halaman sesuai tahap kegiatan
            $redirectRoute = match($kegiatan->tahap) {
                'usulan' => 'kegiatan.show',
                'proposal' => 'kegiatan.proposal.show',
                'pendanaan' => 'kegiatan.pendanaan.show',
                'laporan' => 'kegiatan.laporan.show',
                default => 'kegiatan.show',
            };

            return redirect()->route($redirectRoute, $kegiatan)
                ->with('success', 'Permintaan revisi berhasil dikirim ke Hima.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }

    /**
     * Reject kegiatan (untuk Pembina, Kaprodi, Wadek)
     */
    public function reject(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        $request->validate([
            'comment' => 'required|string',
        ]);

        // Check if user has permission
        if ($kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menolak kegiatan ini saat ini.');
        }

        // Check prodi access (except Wadek)
        if (!$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $userRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'rejected',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Update kegiatan status
            $kegiatan->update([
                'status' => 'rejected',
            ]);

            // Redirect ke halaman sesuai tahap kegiatan
            $redirectRoute = match($kegiatan->tahap) {
                'usulan' => 'kegiatan.show',
                'proposal' => 'kegiatan.proposal.show',
                'pendanaan' => 'kegiatan.pendanaan.show',
                'laporan' => 'kegiatan.laporan.show',
                default => 'kegiatan.show',
            };

            return redirect()->route($redirectRoute, $kegiatan)
                ->with('success', 'Kegiatan telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * Show proposal detail with file viewer
     */
    public function showProposal(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Check access
        if ($user->isHima() && $kegiatan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
        }

        if (!$user->isWadek() && !$user->isHima() && $kegiatan->prodi_id !== $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        // Get proposal file
        $proposalFile = $kegiatan->getFileByTahap('proposal');

        return view('kegiatan.proposal.show', compact('kegiatan', 'proposalFile'));
    }

    /**
     * Show upload proposal form (untuk upload pertama kali atau revisi)
     */
    public function uploadProposalForm(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Hanya Hima pemilik yang bisa upload
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupload proposal.');
        }

        // Cek kondisi upload:
        // 1. Sudah di tahap proposal dengan status draft (pertama kali setelah usulan approved)
        // 2. Sudah di tahap proposal dengan status revision (upload ulang)

        $canUpload = false;

        if ($kegiatan->tahap === 'proposal' && in_array($kegiatan->status, ['draft', 'revision'])) {
            $canUpload = true;
        }

        if (!$canUpload) {
            return redirect()->back()->with('error', 'Proposal hanya dapat diupload pada tahap proposal dengan status draft atau revision.');
        }

        // Get last revision comment if exists
        $lastRevision = ApprovalHistory::where('kegiatan_id', $kegiatan->id)
            ->where('tahap', 'proposal')
            ->where('action', 'revision')
            ->latest()
            ->first();

        // Get existing proposal file if any
        $existingFile = $kegiatan->getFileByTahap('proposal');

        return view('kegiatan.proposal.upload', compact('kegiatan', 'lastRevision', 'existingFile'));
    }

    /**
     * Store uploaded proposal file
     */
    public function storeProposal(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'file' => 'required|mimes:pdf|max:5120', // 5MB max
        ], [
            'file.required' => 'File proposal wajib diupload.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = $kegiatan->id . '_proposal_' . time() . '.pdf';
            $filePath = $file->storeAs('proposals', $fileName, 'public');

            // Jika revisi, hapus file lama (opsional - bisa juga keep untuk history)
            if ($kegiatan->tahap === 'proposal' && $kegiatan->status === 'revision') {
                $oldFile = $kegiatan->getFileByTahap('proposal');
                if ($oldFile && Storage::disk('public')->exists($oldFile->file_path)) {
                    Storage::disk('public')->delete($oldFile->file_path);
                    $oldFile->delete();
                }
            }

            // Create file record
            KegiatanFile::create([
                'kegiatan_id' => $kegiatan->id,
                'tahap' => 'proposal',
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => $user->id,
                'uploaded_at' => now(),
            ]);

            // Status tidak perlu diubah saat upload
            // Status tetap 'draft' atau 'revision' sampai user klik Submit
            // Ini penting agar approver masih bisa melihat kegiatan yang diminta revisi

            return redirect()->route('kegiatan.proposal.show', $kegiatan)
                ->with('success', 'Proposal berhasil diupload! Silakan submit untuk proses persetujuan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupload proposal: ' . $e->getMessage());
        }
    }

    /**
     * Submit proposal for approval
     */
    public function submitProposal(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        if ($kegiatan->tahap !== 'proposal' || !in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Proposal tidak dapat disubmit.');
        }

        // Check if proposal file exists
        $proposalFile = $kegiatan->getFileByTahap('proposal');
        if (!$proposalFile) {
            return redirect()->back()->with('error', 'Harap upload file proposal terlebih dahulu.');
        }

        // Logic sama seperti submit usulan
        $updateData = ['status' => 'submitted'];

        if ($kegiatan->status === 'draft') {
            $updateData['current_approver_role'] = 'pembina_hima';
            $message = 'Proposal berhasil disubmit. Menunggu persetujuan Pembina Hima.';
        } else {
            // Re-submit setelah revisi
            $approverName = match ($kegiatan->current_approver_role) {
                'pembina_hima' => 'Pembina Hima',
                'kaprodi' => 'Kaprodi',
                'wadek_iii' => 'Wadek III',
                default => 'Approver',
            };
            $message = "Proposal berhasil disubmit ulang. Menunggu persetujuan {$approverName}.";
        }

        $kegiatan->update($updateData);

        return redirect()->route('kegiatan.proposal.show', $kegiatan)->with('success', $message);
    }

    /**
     * Delete proposal file
     */
    public function deleteProposal(Kegiatan $kegiatan, KegiatanFile $file)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        if ($kegiatan->tahap !== 'proposal' || $kegiatan->status !== 'draft') {
            return redirect()->back()->with('error', 'File tidak dapat dihapus karena sudah dalam proses persetujuan.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            // Tidak reset tahap, tetap di proposal dengan status draft
            // User bisa upload lagi atau kegiatan tetap di tahap proposal

            return redirect()->route('kegiatan.proposal.show', $kegiatan)
                ->with('success', 'File proposal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    // ==================== PENDANAAN KEGIATAN ====================

    /**
     * Display a listing of pendanaan (RAB stage).
     */
    public function indexPendanaan()
    {
        $user = Auth::user();

        // Base query - exclude rejected kegiatan
        $query = Kegiatan::with(['user', 'prodi', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'pendanaan')
            ->where('status', '!=', 'rejected')
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'rejected');
            });

        // Filter kegiatan tahap pendanaan dengan progressive visibility
        if ($user->isHima()) {
            // Hima melihat semua pendanaan yang dia buat
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat semua pendanaan di prodi mereka (monitoring)
            $query->where('prodi_id', $user->prodi_id);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat pendanaan yang sudah di-approve pembina (exclude completed - sudah pindah tahap)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('current_approver_role', ['kaprodi', 'wadek_iii']);
        } elseif ($user->isWadek()) {
            // Wadek melihat pendanaan yang sudah di-approve kaprodi (exclude completed - sudah pindah tahap)
            $query->where('current_approver_role', 'wadek_iii');
        } else {
            $kegiatans = collect();
            return view('kegiatan.pendanaan.index', compact('kegiatans'));
        }

        // Apply ordering
        $query->latest();

        // Handle pagination
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $kegiatans = $query->get();
        } else {
            $kegiatans = $query->paginate((int)$perPage)->appends(request()->query());
        }

        return view('kegiatan.pendanaan.index', compact('kegiatans'));
    }

    /**
     * Display the specified pendanaan detail.
     */
    public function showPendanaan(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Cek apakah kegiatan sudah pernah sampai tahap pendanaan
        $validTahap = in_array($kegiatan->tahap, ['pendanaan', 'laporan']);
        if (!$validTahap) {
            return redirect()->route('kegiatan.pendanaan.index')
                ->with('error', 'Kegiatan ini belum masuk tahap pendanaan.');
        }

        // Check access
        $hasAccess = false;
        if ($user->isHima() && $kegiatan->user_id === $user->id) {
            $hasAccess = true;
        } elseif ($user->isPembina() && $kegiatan->prodi_id === $user->prodi_id) {
            $hasAccess = true;
        } elseif ($user->isKaprodi() && $kegiatan->prodi_id === $user->prodi_id) {
            $hasAccess = in_array($kegiatan->current_approver_role, ['kaprodi', 'wadek_iii', 'completed']);
        } elseif ($user->isWadek()) {
            $hasAccess = in_array($kegiatan->current_approver_role, ['wadek_iii', 'completed']);
        }

        if (!$hasAccess) {
            return redirect()->route('kegiatan.pendanaan.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pendanaan ini.');
        }

        $kegiatan->load(['user', 'prodi', 'approvalHistories' => function ($query) {
            $query->where('tahap', 'pendanaan')->with('approver')->orderBy('created_at', 'asc');
        }, 'files']);

        $rabFile = $kegiatan->getFileByTahap('pendanaan');

        return view('kegiatan.pendanaan.show', compact('kegiatan', 'rabFile'));
    }

    /**
     * Show the form for uploading RAB.
     */
    public function uploadPendanaanForm(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Cek kondisi upload:
        // 1. Sudah di tahap pendanaan dengan status draft (pertama kali setelah proposal approved)
        // 2. Sudah di tahap pendanaan dengan status revision (upload ulang)

        $canUpload = false;

        if ($kegiatan->tahap === 'pendanaan' && in_array($kegiatan->status, ['draft', 'revision'])) {
            $canUpload = true;
        }

        if (!$canUpload) {
            return redirect()->back()->with('error', 'RAB hanya dapat diupload pada tahap pendanaan dengan status draft atau revision.');
        }

        // Get last revision comment if exists
        $lastRevision = ApprovalHistory::where('kegiatan_id', $kegiatan->id)
            ->where('tahap', 'pendanaan')
            ->where('action', 'revision')
            ->latest()
            ->first();

        // Get existing RAB file if any
        $existingFile = $kegiatan->getFileByTahap('pendanaan');

        return view('kegiatan.pendanaan.upload', compact('kegiatan', 'lastRevision', 'existingFile'));
    }

    /**
     * Store the uploaded RAB file.
     */
    public function storePendanaan(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'total_anggaran' => 'required|numeric|min:0',
            'file_rab' => 'required|file|mimes:pdf|max:5120', // 5MB
        ], [
            'total_anggaran.required' => 'Total anggaran wajib diisi.',
            'total_anggaran.numeric' => 'Total anggaran harus berupa angka.',
            'total_anggaran.min' => 'Total anggaran tidak boleh negatif.',
            'file_rab.required' => 'File RAB wajib diupload.',
            'file_rab.mimes' => 'File RAB harus berformat PDF.',
            'file_rab.max' => 'Ukuran file RAB maksimal 5MB.',
        ]);

        try {
            // Delete old file if exists (re-upload scenario)
            $existingFile = $kegiatan->getFileByTahap('pendanaan');
            if ($existingFile) {
                if (Storage::disk('public')->exists($existingFile->file_path)) {
                    Storage::disk('public')->delete($existingFile->file_path);
                }
                $existingFile->delete();
            }

            // Store new file
            $file = $request->file('file_rab');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('pendanaan', $fileName, 'public');

            // Create file record
            KegiatanFile::create([
                'kegiatan_id' => $kegiatan->id,
                'uploaded_by' => $user->id,
                'tahap' => 'pendanaan',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'uploaded_at' => now(),
            ]);

            // Update total anggaran
            // Status tidak perlu diubah saat upload (tetap draft atau revision)
            // Status baru berubah ke 'submitted' saat user klik tombol Submit
            $kegiatan->update(['total_anggaran' => $request->total_anggaran]);

            return redirect()->route('kegiatan.pendanaan.show', $kegiatan)
                ->with('success', 'File RAB berhasil diupload. Silakan submit untuk persetujuan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Submit pendanaan for approval.
     */
    public function submitPendanaan(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        if ($kegiatan->tahap !== 'pendanaan') {
            return redirect()->back()->with('error', 'Kegiatan belum masuk tahap pendanaan.');
        }

        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Pendanaan sudah dalam proses persetujuan.');
        }

        // Check if RAB file exists
        $rabFile = $kegiatan->getFileByTahap('pendanaan');
        if (!$rabFile) {
            return redirect()->back()->with('error', 'Anda harus upload file RAB terlebih dahulu.');
        }

        $updateData = ['status' => 'submitted'];

        if ($kegiatan->status === 'draft') {
            $updateData['current_approver_role'] = 'pembina_hima';
            $message = 'RAB berhasil disubmit. Menunggu persetujuan Pembina Hima.';
        } else {
            // Re-submit setelah revisi
            $approverName = match ($kegiatan->current_approver_role) {
                'pembina_hima' => 'Pembina Hima',
                'kaprodi' => 'Kaprodi',
                'wadek_iii' => 'Wadek III',
                default => 'Approver',
            };
            $message = "RAB berhasil disubmit ulang. Menunggu persetujuan {$approverName}.";
        }

        $kegiatan->update($updateData);

        return redirect()->route('kegiatan.pendanaan.show', $kegiatan)->with('success', $message);
    }

    /**
     * Delete pendanaan file
     */
    public function deletePendanaan(Kegiatan $kegiatan, KegiatanFile $file)
    {
        $user = Auth::user();

        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        if ($kegiatan->tahap !== 'pendanaan' || $kegiatan->status !== 'draft') {
            return redirect()->back()->with('error', 'File tidak dapat dihapus karena sudah dalam proses persetujuan.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            // Reset total anggaran tapi tetap di tahap pendanaan
            $kegiatan->update([
                'total_anggaran' => null,
            ]);

            return redirect()->route('kegiatan.pendanaan.show', $kegiatan)
                ->with('success', 'File RAB berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of laporan only.
     */
    public function indexLaporan()
    {
        $user = Auth::user();

        // Base query - exclude rejected kegiatan
        $query = Kegiatan::with(['user', 'prodi', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'laporan')
            ->where('status', '!=', 'rejected')
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'rejected');
            });

        // Filter kegiatan tahap laporan berdasarkan role dan visibility
        if ($user->isHima()) {
            // Hima melihat semua laporan yang dia buat (exclude completed)
            $query->where('user_id', $user->id)
                ->where('current_approver_role', '!=', 'completed');
        } elseif ($user->isPembina()) {
            // Pembina melihat semua laporan prodi mereka (exclude completed)
            $query->where('prodi_id', $user->prodi_id)
                ->where('current_approver_role', '!=', 'completed');
        } elseif ($user->isKaprodi()) {
            // Kaprodi hanya melihat laporan yang sudah disetujui Pembina (exclude completed)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('current_approver_role', ['kaprodi', 'wadek_iii']);
        } elseif ($user->isWadek()) {
            // Wadek hanya melihat laporan yang sudah disetujui Kaprodi (exclude completed)
            $query->where('current_approver_role', 'wadek_iii');
        } else {
            $kegiatans = collect();
            return view('kegiatan.laporan.index', compact('kegiatans'));
        }

        // Apply ordering
        $query->latest();

        // Handle pagination
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $kegiatans = $query->get();
        } else {
            $kegiatans = $query->paginate((int)$perPage)->appends(request()->query());
        }

        return view('kegiatan.laporan.index', compact('kegiatans'));
    }

    /**
     * Display the specified laporan.
     */
    public function showLaporan(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Check access
        if ($user->isHima() && $kegiatan->user_id !== $user->id) {
            return redirect()->route('kegiatan.laporan.index')->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        if (!$user->isWadek() && !$user->isHima() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->route('kegiatan.laporan.index')->with('error', 'Anda tidak memiliki akses ke laporan prodi ini.');
        }

        // Load relationships
        $kegiatan->load(['user', 'prodi', 'approvalHistories.approver.role', 'files']);

        // Get laporan file
        $laporanFile = $kegiatan->getFileByTahap('laporan');

        return view('kegiatan.laporan.show', compact('kegiatan', 'laporanFile'));
    }

    /**
     * Show form to upload laporan.
     */
    public function uploadLaporanForm(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Only Hima who owns the kegiatan can upload
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->route('kegiatan.laporan.index')->with('error', 'Anda tidak memiliki akses untuk mengupload laporan.');
        }

        // Must be in laporan tahap
        if ($kegiatan->tahap !== 'laporan') {
            return redirect()->route('kegiatan.laporan.index')->with('error', 'Kegiatan belum masuk tahap laporan.');
        }

        // Can only upload if draft or revision
        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->route('kegiatan.laporan.show', $kegiatan)
                ->with('error', 'Laporan sudah disubmit dan sedang dalam proses persetujuan.');
        }

        $kegiatan->load(['user', 'prodi']);

        return view('kegiatan.laporan.upload', compact('kegiatan'));
    }

    /**
     * Store laporan file.
     */
    public function storeLaporan(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Validate ownership
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Validate status
        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Laporan tidak dapat diupload karena sudah dalam proses persetujuan.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'file.required' => 'File LPJ wajib diupload.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            // Delete old file if exists
            $oldFile = $kegiatan->getFileByTahap('laporan');
            if ($oldFile) {
                if (Storage::disk('public')->exists($oldFile->file_path)) {
                    Storage::disk('public')->delete($oldFile->file_path);
                }
                $oldFile->delete();
            }

            // Store new file
            $file = $request->file('file');
            $filename = 'LPJ_' . $kegiatan->id . '_' . time() . '.pdf';
            $path = $file->storeAs('laporan', $filename, 'public');

            // Save to database
            KegiatanFile::create([
                'kegiatan_id' => $kegiatan->id,
                'tahap' => 'laporan',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $user->id,
            ]);

            // Status tidak perlu diubah saat upload
            // Status tetap 'draft' atau 'revision' sampai user klik Submit

            return redirect()->route('kegiatan.laporan.show', $kegiatan)
                ->with('success', 'File LPJ berhasil diupload. Silakan submit untuk proses persetujuan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Submit laporan for approval.
     */
    public function submitLaporan(Kegiatan $kegiatan)
    {
        $user = Auth::user();

        // Validate ownership
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Check if file exists
        $laporanFile = $kegiatan->getFileByTahap('laporan');
        if (!$laporanFile) {
            return redirect()->back()->with('error', 'Silakan upload file LPJ terlebih dahulu.');
        }

        // Validate status - allow draft or revision
        if (!in_array($kegiatan->status, ['draft', 'revision'])) {
            return redirect()->back()->with('error', 'Laporan sudah disubmit.');
        }

        try {
            $updateData = ['status' => 'submitted'];

            if ($kegiatan->status === 'draft') {
                $updateData['current_approver_role'] = 'pembina_hima';
                $message = 'Laporan berhasil disubmit untuk persetujuan Pembina Hima.';
            } else {
                // Re-submit setelah revisi, current_approver_role tetap sama
                $approverName = match ($kegiatan->current_approver_role) {
                    'pembina_hima' => 'Pembina Hima',
                    'kaprodi' => 'Kaprodi',
                    'wadek_iii' => 'Wadek III',
                    default => 'Approver',
                };
                $message = "Laporan berhasil disubmit ulang. Menunggu persetujuan {$approverName}.";
            }

            $kegiatan->update($updateData);

            return redirect()->route('kegiatan.laporan.show', $kegiatan)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal submit laporan: ' . $e->getMessage());
        }
    }

    /**
     * Delete laporan file.
     */
    public function deleteLaporan(Kegiatan $kegiatan, KegiatanFile $file)
    {
        $user = Auth::user();

        // Validate ownership
        if (!$user->isHima() || $kegiatan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Validate file belongs to kegiatan
        if ($file->kegiatan_id !== $kegiatan->id || $file->tahap !== 'laporan') {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        if ($kegiatan->tahap !== 'laporan' || $kegiatan->status !== 'draft') {
            return redirect()->back()->with('error', 'File tidak dapat dihapus karena sudah dalam proses persetujuan.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete record
            $file->delete();

            return redirect()->route('kegiatan.laporan.show', $kegiatan)
                ->with('success', 'File LPJ berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
