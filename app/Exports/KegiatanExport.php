<?php

namespace App\Exports;

use App\Models\Kegiatan;

class KegiatanExport
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Get collection with filters for export
     */
    public function collection()
    {
        $query = Kegiatan::with(['user', 'prodi', 'jenisKegiatan', 'jenisPendanaan']);

        // Apply filters
        if (!empty($this->filters['search'])) {
            $query->where('nama_kegiatan', 'like', '%' . $this->filters['search'] . '%');
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['tahap'])) {
            $query->where('tahap', $this->filters['tahap']);
        }

        return $query->latest()->get();
    }

    /**
     * Map kegiatan to array for export
     */
    public function map($kegiatan, $index)
    {
        return [
            'No' => $index + 1,
            'Nama Kegiatan' => $kegiatan->nama_kegiatan ?? '-',
            'Deskripsi' => $kegiatan->deskripsi_kegiatan ?? '-',
            'Prodi' => optional($kegiatan->prodi)->nama_prodi ?? '-',
            'Jenis Kegiatan' => optional($kegiatan->jenisKegiatan)->nama ?? '-',
            'Jenis Pendanaan' => optional($kegiatan->jenisPendanaan)->nama ?? '-',
            'Tempat' => $kegiatan->tempat_kegiatan ?? '-',
            'Tanggal Mulai' => $kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d/m/Y') : '-',
            'Tanggal Selesai' => $kegiatan->tanggal_akhir ? \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d/m/Y') : '-',
            'Total Anggaran (Rp)' => $kegiatan->total_anggaran ? 'Rp ' . number_format($kegiatan->total_anggaran, 0, ',', '.') : '-',
            'Tahap' => $kegiatan->tahap ? match($kegiatan->tahap) {
                'usulan' => 'Usulan',
                'proposal' => 'Proposal',
                'pendanaan' => 'Pendanaan',
                'laporan' => 'Laporan',
                default => ucfirst($kegiatan->tahap),
            } : '-',
            'Status' => $kegiatan->status ? match($kegiatan->status) {
                'draft' => 'Draft',
                'dikirim' => 'Dikirim',
                'disetujui' => 'Disetujui',
                'revisi' => 'Revisi',
                'ditolak' => 'Ditolak',
                default => ucfirst($kegiatan->status),
            } : '-',
            'Dibuat Pada' => $kegiatan->created_at ? $kegiatan->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
