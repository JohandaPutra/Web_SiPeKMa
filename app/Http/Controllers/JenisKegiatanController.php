<?php

namespace App\Http\Controllers;

use App\Models\JenisKegiatan;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKegiatans = JenisKegiatan::withCount('kegiatans')
            ->orderBy('nama')
            ->paginate(10);

        return view('jenis-kegiatan.index', compact('jenisKegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis-kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_kegiatans,nama',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama Jenis Kegiatan wajib diisi',
            'nama.unique' => 'Nama Jenis Kegiatan sudah digunakan',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        JenisKegiatan::create($validated);

        return redirect()->route('jenis-kegiatan.index')
            ->with('success', 'Jenis Kegiatan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKegiatan $jenisKegiatan)
    {
        $jenisKegiatan->loadCount('kegiatans');
        return view('jenis-kegiatan.show', compact('jenisKegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKegiatan $jenisKegiatan)
    {
        return view('jenis-kegiatan.edit', compact('jenisKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKegiatan $jenisKegiatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_kegiatans,nama,' . $jenisKegiatan->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama Jenis Kegiatan wajib diisi',
            'nama.unique' => 'Nama Jenis Kegiatan sudah digunakan',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $jenisKegiatan->update($validated);

        return redirect()->route('jenis-kegiatan.index')
            ->with('success', 'Jenis Kegiatan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKegiatan $jenisKegiatan)
    {
        // Check if jenis kegiatan has related kegiatans
        if ($jenisKegiatan->kegiatans()->count() > 0) {
            return redirect()->route('jenis-kegiatan.index')
                ->with('error', 'Jenis Kegiatan tidak dapat dihapus karena masih memiliki kegiatan terkait');
        }

        $jenisKegiatan->delete();

        return redirect()->route('jenis-kegiatan.index')
            ->with('success', 'Jenis Kegiatan berhasil dihapus');
    }
}
