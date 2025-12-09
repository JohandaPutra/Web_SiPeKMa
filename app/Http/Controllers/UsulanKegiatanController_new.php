<?php

namespace App\Http\Controllers;

use App\Models\UsulanKegiatan;
use App\Models\User;
use Illuminate\Http\Request;

class UsulanKegiatanController extends Controller
{
  /**
   * Get default testing user
   */
  private function getDefaultUser()
  {
    // Cek apakah ada user di database
    $defaultUser = User::first();
    if (!$defaultUser) {
      // Buat user default untuk testing
      $defaultUser = User::create([
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
      ]);
    }
    return $defaultUser;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $usulanKegiatans = UsulanKegiatan::with('user')->latest()->get();
    return view('usulan-kegiatan.index', compact('usulanKegiatans'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('usulan-kegiatan.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nama_kegiatan' => 'required|string|max:255',
      'deskripsi_kegiatan' => 'required|string',
      'jenis_kegiatan' => 'required|in:seminar,workshop,pelatihan,lomba,lainnya',
      'tempat_kegiatan' => 'required|string|max:255',
      'jenis_pendanaan' => 'required|in:mandiri,sponsor,hibah,internal',
      'tanggal_mulai' => 'required|date',
      'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
      'status_kegiatan' => 'nullable|in:draft,submitted,review,approved,rejected',
    ]);

    // Gunakan user default untuk testing
    $defaultUser = $this->getDefaultUser();

    try {
      UsulanKegiatan::create([
        'user_id' => $defaultUser->id,
        'nama_kegiatan' => $request->nama_kegiatan,
        'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
        'jenis_kegiatan' => $request->jenis_kegiatan,
        'tempat_kegiatan' => $request->tempat_kegiatan,
        'jenis_pendanaan' => $request->jenis_pendanaan,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_akhir' => $request->tanggal_akhir,
        'status_kegiatan' => $request->status_kegiatan ?? 'draft',
      ]);

      return redirect()->route('usulan-kegiatan.index')
        ->with('success', 'Usulan kegiatan berhasil dibuat.');
    } catch (\Exception $e) {
      return redirect()->back()
        ->withInput()
        ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(UsulanKegiatan $usulanKegiatan)
  {
    $usulanKegiatan->load('user');
    return view('usulan-kegiatan.show', compact('usulanKegiatan'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(UsulanKegiatan $usulanKegiatan)
  {
    return view('usulan-kegiatan.edit', compact('usulanKegiatan'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, UsulanKegiatan $usulanKegiatan)
  {
    $request->validate([
      'nama_kegiatan' => 'required|string|max:255',
      'deskripsi_kegiatan' => 'required|string',
      'jenis_kegiatan' => 'required|in:seminar,workshop,pelatihan,lomba,lainnya',
      'tempat_kegiatan' => 'required|string|max:255',
      'jenis_pendanaan' => 'required|in:mandiri,sponsor,hibah,internal',
      'tanggal_mulai' => 'required|date',
      'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
      'status_kegiatan' => 'required|in:draft,submitted,review,approved,rejected',
    ]);

    $usulanKegiatan->update($request->all());

    return redirect()->route('usulan-kegiatan.index')
      ->with('success', 'Usulan kegiatan berhasil diperbarui.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(UsulanKegiatan $usulanKegiatan)
  {
    $usulanKegiatan->delete();

    return redirect()->route('usulan-kegiatan.index')
      ->with('success', 'Usulan kegiatan berhasil dihapus.');
  }
}
