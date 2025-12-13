<?php

namespace App\Http\Controllers;

use App\Models\JenisPendanaan;
use Illuminate\Http\Request;

class JenisPendanaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisPendanaans = JenisPendanaan::withCount('kegiatans')
            ->orderBy('nama')
            ->paginate(10);

        return view('jenis-pendanaan.index', compact('jenisPendanaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis-pendanaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_pendanaans,nama',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama Jenis Pendanaan wajib diisi',
            'nama.unique' => 'Nama Jenis Pendanaan sudah digunakan',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        JenisPendanaan::create($validated);

        return redirect()->route('jenis-pendanaan.index')
            ->with('success', 'Jenis Pendanaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisPendanaan $jenisPendanaan)
    {
        $jenisPendanaan->loadCount('kegiatans');
        return view('jenis-pendanaan.show', compact('jenisPendanaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisPendanaan $jenisPendanaan)
    {
        return view('jenis-pendanaan.edit', compact('jenisPendanaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisPendanaan $jenisPendanaan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_pendanaans,nama,' . $jenisPendanaan->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama Jenis Pendanaan wajib diisi',
            'nama.unique' => 'Nama Jenis Pendanaan sudah digunakan',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $jenisPendanaan->update($validated);

        return redirect()->route('jenis-pendanaan.index')
            ->with('success', 'Jenis Pendanaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisPendanaan $jenisPendanaan)
    {
        // Check if jenis pendanaan has related kegiatans
        if ($jenisPendanaan->kegiatans()->count() > 0) {
            return redirect()->route('jenis-pendanaan.index')
                ->with('error', 'Jenis Pendanaan tidak dapat dihapus karena masih memiliki kegiatan terkait');
        }

        $jenisPendanaan->delete();

        return redirect()->route('jenis-pendanaan.index')
            ->with('success', 'Jenis Pendanaan berhasil dihapus');
    }
}
