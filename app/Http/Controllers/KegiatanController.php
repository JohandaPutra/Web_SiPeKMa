<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanFile;
use App\Models\ApprovalHistory;
use App\Exports\KegiatanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class KegiatanController extends Controller
{
    /**
     * Display riwayat kegiatan (all stages).
     */
    public function riwayat()
    {
        $user = Auth::user();

        // Base query dengan eager loading
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver', 'files']);

        // Filter berdasarkan role
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            // Super Admin dan Admin melihat SEMUA kegiatan dari SEMUA prodi
            // Tidak ada filter prodi_id
        } elseif ($user->isHima() || $user->isPembina() || $user->isKaprodi()) {
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

        if (request('status')) {
            $query->where('status', request('status'));
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

        return view('kegiatan.riwayat.index', compact('kegiatans'));
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
        $kegiatan->load(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver', 'files']);

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
     * PENTING: Hanya tampilkan usulan yang BELUM selesai disetujui
     * Usulan yang sudah disetujui penuh akan pindah ke tahap proposal (otomatis)
     */
    public function index()
    {
        $user = Auth::user();

        // Base query - HANYA usulan yang belum selesai
        // Exclude: ditolak DAN disetujui (sudah pindah ke proposal)
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver'])
            ->where('tahap', 'usulan')
            ->whereNotIn('status', ['ditolak', 'disetujui']) // Yang disetujui sudah pindah tahap
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'ditolak');
            });

        // Filter kegiatan tahap usulan saja
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            // Super Admin dan Admin melihat SEMUA usulan dari SEMUA prodi yang masih dalam proses
            // No additional filter needed
        } elseif ($user->isHima()) {
            // Hima melihat usulan yang dia buat (draft, dikirim, revisi)
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat usulan prodi mereka yang sudah disubmit (tidak termasuk draft)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('status', ['dikirim', 'revisi']);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat usulan prodi mereka yang sudah disetujui pembina (menunggu approval kaprodi)
            $query->where('prodi_id', $user->prodi_id)
                ->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'pembina_hima')
                      ->where('tahap', 'usulan')
                      ->where('action', 'disetujui');
                });
        } elseif ($user->isWadek()) {
            // Wadek melihat usulan yang sudah disetujui kaprodi (menunggu approval wadek)
            $query->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'kaprodi')
                      ->where('tahap', 'usulan')
                      ->where('action', 'disetujui');
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
     * PENTING: Hanya tampilkan proposal yang BELUM selesai disetujui
     * Proposal yang sudah disetujui penuh akan pindah ke tahap pendanaan (otomatis)
     */
    public function indexProposal()
    {
        $user = Auth::user();

        // Base query - HANYA proposal yang belum selesai
        // Exclude: ditolak DAN disetujui (sudah pindah ke pendanaan)
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'proposal')
            ->whereNotIn('status', ['ditolak', 'disetujui']) // Yang disetujui sudah pindah tahap
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'ditolak');
            });

        // Filter kegiatan tahap proposal berdasarkan role dan visibility
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            // Super Admin dan Admin melihat SEMUA proposal dari SEMUA prodi yang masih dalam proses
            // No additional filter needed
        } elseif ($user->isHima()) {
            // Hima melihat semua proposal yang dia buat (draft, dikirim, revisi)
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat proposal prodi mereka yang sudah disubmit (tidak termasuk draft)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('status', ['dikirim', 'revisi']);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat proposal yang sudah disetujui Pembina (menunggu approval kaprodi)
            $query->where('prodi_id', $user->prodi_id)
                ->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'pembina_hima')
                      ->where('tahap', 'proposal')
                      ->where('action', 'disetujui');
                });
        } elseif ($user->isWadek()) {
            // Wadek melihat proposal yang sudah disetujui Kaprodi (menunggu approval wadek)
            $query->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'kaprodi')
                      ->where('tahap', 'proposal')
                      ->where('action', 'disetujui');
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

        $jenisKegiatans = \App\Models\JenisKegiatan::where('is_active', true)->get();
        $jenisPendanaans = \App\Models\JenisPendanaan::where('is_active', true)->get();

        return view('kegiatan.create', compact('jenisKegiatans', 'jenisPendanaans'));
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
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'tempat_kegiatan' => 'required|string|max:255',
            'jenis_pendanaan_id' => 'required|exists:jenis_pendanaans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            Kegiatan::create([
                'user_id' => $user->id,
                'prodi_id' => $user->prodi_id,
                'nama_kegiatan' => $request->nama_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'jenis_pendanaan_id' => $request->jenis_pendanaan_id,
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

        // Super Admin dan Admin memiliki akses penuh
        if (!$user->isSuperAdmin() && !$user->isRegularAdmin()) {
            // Check permission untuk role lain
            if ($user->isHima() && $kegiatan->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
            }

            if (($user->isPembina() || $user->isKaprodi()) && $kegiatan->prodi_id !== $user->prodi_id) {
                abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
            }
        }

        $kegiatan->load(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver.role']);

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

        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah dalam proses persetujuan.');
        }

        $jenisKegiatans = \App\Models\JenisKegiatan::where('is_active', true)->get();
        $jenisPendanaans = \App\Models\JenisPendanaan::where('is_active', true)->get();

        return view('kegiatan.edit', compact('kegiatan', 'jenisKegiatans', 'jenisPendanaans'));
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

        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah dalam proses persetujuan.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatans,id',
            'tempat_kegiatan' => 'required|string|max:255',
            'jenis_pendanaan_id' => 'required|exists:jenis_pendanaans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            $kegiatan->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
                'tempat_kegiatan' => $request->tempat_kegiatan,
                'jenis_pendanaan_id' => $request->jenis_pendanaan_id,
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

        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->back()->with('error', 'Kegiatan sudah disubmit.');
        }

        // Jika status revision, current_approver_role sudah di-set (tetap ke yang request revision)
        // Jika draft baru, mulai dari pembina_hima
        $updateData = ['status' => 'dikirim'];

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
     * Approve kegiatan (untuk Pembina, Kaprodi, Wadek, Super Admin, dan Admin)
     */
    public function approve(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        // Super Admin dan Admin dapat approve tanpa pembatasan
        $isSuperAdminOrAdmin = $user->isSuperAdmin() || $user->isRegularAdmin();

        // Check if user has permission to approve
        if (!$isSuperAdminOrAdmin && $kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menyetujui kegiatan ini saat ini.');
        }

        // Check prodi access (except Wadek, Super Admin, and Admin)
        if (!$isSuperAdminOrAdmin && !$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Tentukan effective role untuk approval
            // Super Admin bypass sebagai 'wadek_iii' (langsung completed)
            // Admin biasa mengikuti current_approver_role atau role mereka
            $effectiveRole = $userRole;
            
            if ($user->isSuperAdmin()) {
                // Super Admin selalu bertindak sebagai Wadek III (approval terakhir)
                $effectiveRole = 'wadek_iii';
            } elseif ($user->isRegularAdmin()) {
                // Admin menggunakan current_approver_role yang sedang menunggu
                // Jika tidak ada current_approver_role, gunakan pembina_hima sebagai default
                $effectiveRole = $kegiatan->current_approver_role ?? 'pembina_hima';
            }
            
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $effectiveRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'disetujui',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Determine next approver berdasarkan effective role
            $nextApprover = match($effectiveRole) {
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

                // Status: 'disetujui' jika laporan selesai, 'draft' untuk tahap lainnya pindah
                $newStatus = ($currentTahap === 'laporan') ? 'disetujui' : 'draft';

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

            // Redirect berdasarkan tahap - kembali ke index untuk Wadek/Admin/Super Admin, show untuk approver lain
            if ($userRole === 'wadek_iii' || $isSuperAdminOrAdmin) {
                // Wadek III, Admin, Super Admin kembali ke index tahap yang baru saja disetujui (SEBELUM pindah tahap)
                $redirectTahap = match($currentTahap ?? $kegiatan->tahap) {
                    'usulan' => 'kegiatan.index',
                    'proposal' => 'kegiatan.proposal.index',
                    'pendanaan' => 'kegiatan.pendanaan.index',
                    'laporan' => 'kegiatan.index',
                    default => 'kegiatan.index',
                };
                return redirect()->route($redirectTahap)->with('success', $message);
            } else {
                // Pembina/Kaprodi tetap ke show page sesuai tahap
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
     * Request revisi (untuk Pembina, Kaprodi, Wadek, Super Admin, dan Admin)
     */
    public function revisi(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        $request->validate([
            'comment' => 'required|string',
        ]);

        // Super Admin dan Admin dapat meminta revisi tanpa pembatasan
        $isSuperAdminOrAdmin = $user->isSuperAdmin() || $user->isRegularAdmin();

        // Check if user has permission
        if (!$isSuperAdminOrAdmin && $kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk meminta revisi saat ini.');
        }

        // Check prodi access (except Wadek, Super Admin, and Admin)
        if (!$isSuperAdminOrAdmin && !$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Tentukan effective role untuk revisi
            $effectiveRole = $userRole;
            
            if ($user->isSuperAdmin()) {
                // Super Admin selalu bertindak sebagai Wadek III
                $effectiveRole = 'wadek_iii';
            } elseif ($user->isRegularAdmin()) {
                // Admin menggunakan current_approver_role yang sedang menunggu
                $effectiveRole = $kegiatan->current_approver_role ?? 'pembina_hima';
            }
            
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $effectiveRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'revisi',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Update kegiatan status - kembali ke Hima untuk revisi
            // current_approver_role tetap sama (akan kembali ke role yang request revision)
            $kegiatan->update([
                'status' => 'revisi',
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
     * Tolak kegiatan (untuk Pembina, Kaprodi, Wadek, Super Admin, dan Admin)
     */
    public function tolak(Request $request, Kegiatan $kegiatan)
    {
        $user = Auth::user();
        $userRole = $user->role->name;

        $request->validate([
            'comment' => 'required|string',
        ]);

        // Super Admin dan Admin dapat menolak tanpa pembatasan
        $isSuperAdminOrAdmin = $user->isSuperAdmin() || $user->isRegularAdmin();

        // Check if user has permission
        if (!$isSuperAdminOrAdmin && $kegiatan->current_approver_role !== $userRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki wewenang untuk menolak kegiatan ini saat ini.');
        }

        // Check prodi access (except Wadek, Super Admin, and Admin)
        if (!$isSuperAdminOrAdmin && !$user->isWadek() && $kegiatan->prodi_id !== $user->prodi_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kegiatan prodi ini.');
        }

        try {
            // Tentukan effective role untuk penolakan
            $effectiveRole = $userRole;
            
            if ($user->isSuperAdmin()) {
                // Super Admin selalu bertindak sebagai Wadek III
                $effectiveRole = 'wadek_iii';
            } elseif ($user->isRegularAdmin()) {
                // Admin menggunakan current_approver_role yang sedang menunggu
                $effectiveRole = $kegiatan->current_approver_role ?? 'pembina_hima';
            }
            
            // Record approval history
            ApprovalHistory::create([
                'kegiatan_id' => $kegiatan->id,
                'approver_user_id' => $user->id,
                'approver_role' => $effectiveRole,
                'tahap' => $kegiatan->tahap,
                'action' => 'ditolak',
                'comment' => $request->comment,
                'approved_at' => now(),
            ]);

            // Update kegiatan status
            $kegiatan->update([
                'status' => 'ditolak',
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

        // Check access - Super Admin dan Admin memiliki akses penuh
        if (!$user->isSuperAdmin() && !$user->isRegularAdmin()) {
            if ($user->isHima() && $kegiatan->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke kegiatan ini.');
            }

            if (!$user->isWadek() && !$user->isHima() && $kegiatan->prodi_id !== $user->prodi_id) {
                abort(403, 'Anda tidak memiliki akses ke kegiatan prodi ini.');
            }
        }

        // Load relationships
        $kegiatan->load(['jenisKegiatan', 'jenisPendanaan']);

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

        if ($kegiatan->tahap === 'proposal' && in_array($kegiatan->status, ['draft', 'revisi'])) {
            $canUpload = true;
        }

        if (!$canUpload) {
            return redirect()->back()->with('error', 'Proposal hanya dapat diupload pada tahap proposal dengan status draft atau revision.');
        }

        // Get last revision comment if exists
        $lastRevision = ApprovalHistory::where('kegiatan_id', $kegiatan->id)
            ->where('tahap', 'proposal')
            ->where('action', 'revisi')
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
            if ($kegiatan->tahap === 'proposal' && $kegiatan->status === 'revisi') {
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
            // Status tetap 'draft' atau 'revisi' sampai user klik Submit
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

        if ($kegiatan->tahap !== 'proposal' || !in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->back()->with('error', 'Proposal tidak dapat disubmit.');
        }

        // Check if proposal file exists
        $proposalFile = $kegiatan->getFileByTahap('proposal');
        if (!$proposalFile) {
            return redirect()->back()->with('error', 'Harap upload file proposal terlebih dahulu.');
        }

        // Logic sama seperti submit usulan
        $updateData = ['status' => 'dikirim'];

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
     * PENTING: Hanya tampilkan pendanaan yang BELUM selesai disetujui
     * Pendanaan yang sudah disetujui penuh akan pindah ke tahap laporan (otomatis)
     */
    public function indexPendanaan()
    {
        $user = Auth::user();

        // Base query - HANYA pendanaan yang belum selesai
        // Exclude: ditolak DAN disetujui (sudah pindah ke laporan)
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'pendanaan')
            ->whereNotIn('status', ['ditolak', 'disetujui']) // Yang disetujui sudah pindah tahap
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'ditolak');
            });

        // Filter kegiatan tahap pendanaan dengan progressive visibility
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            // Super Admin dan Admin melihat SEMUA pendanaan dari SEMUA prodi yang masih dalam proses
            // No additional filter needed
        } elseif ($user->isHima()) {
            // Hima melihat semua pendanaan yang dia buat (draft, dikirim, revisi)
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat pendanaan prodi mereka yang sudah disubmit (tidak termasuk draft)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('status', ['dikirim', 'revisi']);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat pendanaan yang sudah disetujui Pembina (menunggu approval kaprodi)
            $query->where('prodi_id', $user->prodi_id)
                ->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'pembina_hima')
                      ->where('tahap', 'pendanaan')
                      ->where('action', 'disetujui');
                });
        } elseif ($user->isWadek()) {
            // Wadek melihat pendanaan yang sudah disetujui Kaprodi (menunggu approval wadek)
            $query->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'kaprodi')
                      ->where('tahap', 'pendanaan')
                      ->where('action', 'disetujui');
                });
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
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            $hasAccess = true;
        } elseif ($user->isHima() && $kegiatan->user_id === $user->id) {
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

        $kegiatan->load(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories' => function ($query) {
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

        if ($kegiatan->tahap === 'pendanaan' && in_array($kegiatan->status, ['draft', 'revisi'])) {
            $canUpload = true;
        }

        if (!$canUpload) {
            return redirect()->back()->with('error', 'RAB hanya dapat diupload pada tahap pendanaan dengan status draft atau revision.');
        }

        // Get last revision comment if exists
        $lastRevision = ApprovalHistory::where('kegiatan_id', $kegiatan->id)
            ->where('tahap', 'pendanaan')
            ->where('action', 'revisi')
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
            // Status baru berubah ke 'dikirim' saat user klik tombol Submit
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

        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->back()->with('error', 'Pendanaan sudah dalam proses persetujuan.');
        }

        // Check if RAB file exists
        $rabFile = $kegiatan->getFileByTahap('pendanaan');
        if (!$rabFile) {
            return redirect()->back()->with('error', 'Anda harus upload file RAB terlebih dahulu.');
        }

        $updateData = ['status' => 'dikirim'];

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
     * PENTING: Hanya tampilkan laporan yang BELUM selesai disetujui
     * Laporan yang sudah disetujui penuh (status=disetujui) tidak ditampilkan di list, hanya di riwayat
     */
    public function indexLaporan()
    {
        $user = Auth::user();

        // Base query - HANYA laporan yang belum selesai
        // Exclude: ditolak DAN disetujui (completed)
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver', 'files'])
            ->where('tahap', 'laporan')
            ->whereNotIn('status', ['ditolak', 'disetujui']) // Yang disetujui sudah completed
            ->whereDoesntHave('approvalHistories', function($q) {
                $q->where('action', 'ditolak');
            });

        // Filter kegiatan tahap laporan berdasarkan role dan visibility
        if ($user->isSuperAdmin() || $user->isRegularAdmin()) {
            // Super Admin dan Admin melihat SEMUA laporan dari SEMUA prodi yang masih dalam proses
            // No additional filter needed
        } elseif ($user->isHima()) {
            // Hima melihat laporan yang dia buat (draft, dikirim, revisi)
            $query->where('user_id', $user->id);
        } elseif ($user->isPembina()) {
            // Pembina melihat laporan prodi mereka yang sudah disubmit (tidak termasuk draft)
            $query->where('prodi_id', $user->prodi_id)
                ->whereIn('status', ['dikirim', 'revisi']);
        } elseif ($user->isKaprodi()) {
            // Kaprodi melihat laporan yang sudah disetujui Pembina (menunggu approval kaprodi)
            $query->where('prodi_id', $user->prodi_id)
                ->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'pembina_hima')
                      ->where('tahap', 'laporan')
                      ->where('action', 'disetujui');
                });
        } elseif ($user->isWadek()) {
            // Wadek melihat laporan yang sudah disetujui Kaprodi (menunggu approval wadek)
            $query->where('status', 'dikirim')
                ->whereHas('approvalHistories', function($q) {
                    $q->where('approver_role', 'kaprodi')
                      ->where('tahap', 'laporan')
                      ->where('action', 'disetujui');
                });
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

        // Check access - Super Admin dan Admin memiliki akses penuh
        if (!$user->isSuperAdmin() && !$user->isRegularAdmin()) {
            if ($user->isHima() && $kegiatan->user_id !== $user->id) {
                return redirect()->route('kegiatan.laporan.index')->with('error', 'Anda tidak memiliki akses ke laporan ini.');
            }

            if (!$user->isWadek() && !$user->isHima() && $kegiatan->prodi_id !== $user->prodi_id) {
                return redirect()->route('kegiatan.laporan.index')->with('error', 'Anda tidak memiliki akses ke laporan prodi ini.');
            }
        }

        // Load relationships
        $kegiatan->load(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan', 'approvalHistories.approver.role', 'files']);

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
        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
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
        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
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
            // Status tetap 'draft' atau 'revisi' sampai user klik Submit

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
        if (!in_array($kegiatan->status, ['draft', 'revisi'])) {
            return redirect()->back()->with('error', 'Laporan sudah disubmit.');
        }

        try {
            $updateData = ['status' => 'dikirim'];

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

    /**
     * Export kegiatan to Excel/CSV
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
            'tahap',
            'prodi_id',
            'jenis_kegiatan_id',
            'jenis_pendanaan_id',
            'date_from',
            'date_to'
        ]);

        $filename = 'kegiatan_' . date('Y-m-d_His');

        // Get filtered data
        $exporter = new KegiatanExport($filters);
        $kegiatans = $exporter->collection();

        // Map data
        $data = $kegiatans->map(function ($kegiatan, $index) use ($exporter) {
            return $exporter->map($kegiatan, $index);
        });

        // Determine export type (default to xlsx)
        $type = $request->get('export_type', 'xlsx');
        $extension = $type === 'csv' ? 'csv' : 'xlsx';

        // Export using FastExcel
        return (new FastExcel($data))->download($filename . '.' . $extension);
    }
}
