<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::withCount('users')
            ->orderBy('kode_prodi')
            ->paginate(10);

        return view('prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('prodi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:100',
        ], [
            'kode_prodi.required' => 'Kode Prodi wajib diisi',
            'kode_prodi.unique' => 'Kode Prodi sudah digunakan',
            'nama_prodi.required' => 'Nama Prodi wajib diisi',
        ]);

        Prodi::create($validated);

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prodi $prodi)
    {
        $prodi->loadCount('users', 'kegiatans');
        return view('prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi)
    {
        return view('prodi.edit', compact('prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:100',
        ], [
            'kode_prodi.required' => 'Kode Prodi wajib diisi',
            'kode_prodi.unique' => 'Kode Prodi sudah digunakan',
            'nama_prodi.required' => 'Nama Prodi wajib diisi',
        ]);

        $prodi->update($validated);

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi)
    {
        // Check if prodi has users
        if ($prodi->users()->count() > 0) {
            return redirect()->route('prodi.index')
                ->with('error', 'Program Studi tidak dapat dihapus karena masih memiliki user terkait');
        }

        // Check if prodi has kegiatans
        if ($prodi->kegiatans()->count() > 0) {
            return redirect()->route('prodi.index')
                ->with('error', 'Program Studi tidak dapat dihapus karena masih memiliki kegiatan terkait');
        }

        $prodi->delete();

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil dihapus');
    }
}
