# RINGKASAN AKSES ROLE - SISTEM SIPEKMA

## Daftar Role
1. **Super Admin** (`super_admin`) - Super Administrator
2. **Admin** (`admin`) - Administrator
3. **Wadek III** (`wadek_iii`) - Wakil Dekan III
4. **Kaprodi** (`kaprodi`) - Kepala Program Studi
5. **Pembina Hima** (`pembina_hima`) - Pembina Himpunan Mahasiswa
6. **Hima** (`hima`) - Himpunan Mahasiswa

---

## 1️⃣ SUPER ADMIN (`super_admin`)

### Akses Penuh Tanpa Batasan
✅ **Melihat Semua Data**
- Semua kegiatan dari semua prodi
- Semua tahap (Usulan, Proposal, Pendanaan, Laporan)
- Semua status (Draft, Dikirim, Revisi, Disetujui, Ditolak)

✅ **Approval**
- Bertindak sebagai **Wadek III** (approval terakhir)
- Langsung menyelesaikan approval → pindah tahap berikutnya
- Tidak perlu menunggu workflow Pembina → Kaprodi → Wadek
- Dapat approve/revisi/tolak di tahap manapun

✅ **Export**
- Export semua data kegiatan (Excel & CSV)
- Tidak ada filter prodi

✅ **Management**
- Akses penuh ke User Management
- Akses penuh ke semua menu administrator

---

## 2️⃣ ADMIN (`admin`)

### Akses Penuh dengan Workflow Normal
✅ **Melihat Semua Data**
- Semua kegiatan dari semua prodi
- Semua tahap (Usulan, Proposal, Pendanaan, Laporan)
- Semua status (Draft, Dikirim, Revisi, Disetujui, Ditolak)

✅ **Approval**
- Bertindak sesuai `current_approver_role` yang sedang menunggu
- Mengikuti workflow normal: Pembina → Kaprodi → Wadek III
- Approval berlanjut ke approver berikutnya
- Dapat approve/revisi/tolak di tahap manapun

**Contoh Workflow:**
```
Status: current_approver_role = 'pembina_hima'
Admin Approve → Lanjut ke 'kaprodi'

Admin Approve lagi → Lanjut ke 'wadek_iii'

Admin Approve lagi → Completed & pindah tahap
```

✅ **Export**
- Export semua data kegiatan (Excel & CSV)
- Tidak ada filter prodi

✅ **Management**
- Akses ke semua menu (kecuali User Management khusus Super Admin)

---

## 3️⃣ WADEK III (`wadek_iii`)

### Akses Multi-Prodi dengan Approval Terakhir
✅ **Melihat Data**
- **Riwayat**: Semua kegiatan dari semua prodi
- **Usulan**: Usulan yang sudah disetujui Kaprodi (menunggu Wadek)
- **Proposal**: Proposal yang sudah disetujui Kaprodi (menunggu Wadek)
- **Pendanaan**: Semua pendanaan (sudah completed di current_approver)
- **Laporan**: Semua laporan

✅ **Approval**
- Approval terakhir dalam workflow
- Setelah approve → kegiatan pindah tahap berikutnya
- Dapat approve/revisi/tolak kegiatan yang sudah disetujui Kaprodi

✅ **Export**
- Export semua data kegiatan dari semua prodi

❌ **Pembatasan**
- Tidak bisa approve kegiatan yang belum sampai ke Wadek
- Hanya melihat kegiatan yang sudah masuk ke level Wadek

---

## 4️⃣ KAPRODI (`kaprodi`)

### Akses Terbatas Per Prodi
✅ **Melihat Data**
- **Riwayat**: Hanya kegiatan di prodi sendiri
- **Usulan**: Usulan prodi sendiri yang sudah disetujui Pembina
- **Proposal**: Proposal prodi sendiri yang sudah disetujui Pembina
- **Pendanaan**: Pendanaan prodi sendiri (jika current_approver >= kaprodi)
- **Laporan**: Laporan prodi sendiri

✅ **Approval**
- Approve kegiatan prodi sendiri setelah Pembina approve
- Setelah approve → lanjut ke Wadek III
- Dapat approve/revisi/tolak usulan & proposal di prodi sendiri

✅ **Export**
- Export kegiatan prodi sendiri

❌ **Pembatasan**
- Tidak bisa melihat kegiatan prodi lain
- Tidak bisa approve kegiatan yang belum disetujui Pembina
- Tidak bisa approve kegiatan draft (yang belum dikirim)

---

## 5️⃣ PEMBINA HIMA (`pembina_hima`)

### Akses Terbatas Per Prodi
✅ **Melihat Data**
- **Riwayat**: Hanya kegiatan di prodi sendiri
- **Usulan**: Usulan prodi sendiri yang sudah dikirim (status: dikirim/revisi)
- **Proposal**: Proposal prodi sendiri yang sudah dikirim
- **Pendanaan**: Pendanaan prodi sendiri
- **Laporan**: Laporan prodi sendiri

✅ **Approval**
- Approval pertama dalam workflow
- Setelah approve → lanjut ke Kaprodi
- Dapat approve/revisi/tolak usulan & proposal di prodi sendiri

✅ **Export**
- Export kegiatan prodi sendiri

❌ **Pembatasan**
- Tidak bisa melihat kegiatan prodi lain
- Tidak bisa melihat kegiatan draft (yang belum dikirim)
- Hanya bisa approve kegiatan yang statusnya 'dikirim' atau 'revisi'

---

## 6️⃣ HIMA (`hima`)

### Akses Terbatas Kegiatan Sendiri
✅ **Melihat Data**
- **Riwayat**: Hanya kegiatan yang dibuat sendiri
- **Usulan**: Hanya usulan yang dibuat sendiri (semua status)
- **Proposal**: Hanya proposal yang dibuat sendiri
- **Pendanaan**: Hanya pendanaan yang dibuat sendiri
- **Laporan**: Hanya laporan yang dibuat sendiri

✅ **Create & Upload**
- Membuat kegiatan baru (tahap usulan)
- Upload file proposal/pendanaan/laporan
- Edit kegiatan sendiri (jika status draft atau revisi)
- Submit kegiatan untuk review

✅ **Export**
- Export kegiatan sendiri

❌ **Pembatasan**
- Tidak bisa melihat kegiatan orang lain
- Tidak bisa approve kegiatan
- Tidak bisa melihat kegiatan di prodi lain
- Tidak bisa edit kegiatan yang sudah dikirim (kecuali status revisi)

---

## Tabel Perbandingan Akses

| Fitur | Super Admin | Admin | Wadek III | Kaprodi | Pembina | Hima |
|-------|-------------|-------|-----------|---------|---------|------|
| Lihat Semua Prodi | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Lihat Prodi Sendiri | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Lihat Draft Orang Lain | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Approve Langsung | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Approve Normal | - | ✅ | ✅ | ✅ | ✅ | ❌ |
| Revisi/Tolak | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Create Kegiatan | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Upload File | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Export Semua | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| User Management | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## Workflow Approval

```
1. HIMA membuat usulan → status: draft
2. HIMA submit usulan → status: dikirim, current_approver: pembina_hima

3. PEMBINA approve → current_approver: kaprodi
   atau PEMBINA revisi → status: revisi, current_approver: pembina_hima
   atau PEMBINA tolak → status: ditolak

4. KAPRODI approve → current_approver: wadek_iii
   atau KAPRODI revisi → status: revisi, current_approver: kaprodi
   atau KAPRODI tolak → status: ditolak

5. WADEK III approve → current_approver: completed, pindah tahap berikutnya
   atau WADEK III revisi → status: revisi, current_approver: wadek_iii
   atau WADEK III tolak → status: ditolak

SPECIAL CASE:
- SUPER ADMIN approve → Langsung completed & pindah tahap (skip workflow)
- ADMIN approve → Mengikuti workflow normal sesuai current_approver_role
```

---

## File yang Diupdate

### Controller
- `app/Http/Controllers/KegiatanController.php`
  - ✅ Method `riwayat()` - Filter akses per role
  - ✅ Method `index()` - Filter usulan per role
  - ✅ Method `proposalIndex()` - Filter proposal per role
  - ✅ Method `show()` - Akses detail kegiatan
  - ✅ Method `showProposal()` - Akses proposal
  - ✅ Method `showPendanaan()` - Akses pendanaan
  - ✅ Method `showLaporan()` - Akses laporan
  - ✅ Method `approve()` - Logika approval
  - ✅ Method `revisi()` - Logika revisi
  - ✅ Method `tolak()` - Logika penolakan

### Model
- `app/Models/User.php`
  - ✅ `isSuperAdmin()` - Check role super_admin
  - ✅ `isRegularAdmin()` - Check role admin
  - ✅ `isAnyAdmin()` - Check super_admin atau admin
  - ✅ `isPembina()` - Check role pembina_hima
  - ✅ `isKaprodi()` - Check role kaprodi
  - ✅ `isWadek()` - Check role wadek_iii
  - ✅ `isHima()` - Check role hima

### Views
- `resources/views/kegiatan/show.blade.php`
- `resources/views/kegiatan/proposal/show.blade.php`
- `resources/views/kegiatan/pendanaan/show.blade.php`
- `resources/views/kegiatan/laporan/show.blade.php`

---

## Testing Checklist

### Super Admin
- [ ] Login sebagai Super Admin
- [ ] Verifikasi bisa melihat semua kegiatan dari semua prodi
- [ ] Approve usulan → Langsung ke Proposal (skip workflow)
- [ ] Cek UI menampilkan "Anda bertindak sebagai Wadek III (Approval Langsung)"
- [ ] Test revisi dan tolak kegiatan

### Admin
- [ ] Login sebagai Admin
- [ ] Verifikasi bisa melihat semua kegiatan dari semua prodi
- [ ] Approve usulan (saat current_approver = pembina) → Lanjut ke Kaprodi
- [ ] Approve lagi (saat current_approver = kaprodi) → Lanjut ke Wadek
- [ ] Approve lagi (saat current_approver = wadek) → Completed & pindah tahap
- [ ] Cek UI menampilkan role yang sesuai (Pembina/Kaprodi/Wadek III)

### Wadek III
- [ ] Login sebagai Wadek III
- [ ] Verifikasi bisa melihat semua kegiatan dari semua prodi
- [ ] Hanya bisa approve kegiatan yang sudah disetujui Kaprodi
- [ ] Test approve, revisi, tolak

### Kaprodi
- [ ] Login sebagai Kaprodi
- [ ] Verifikasi hanya melihat kegiatan prodi sendiri
- [ ] Tidak bisa melihat kegiatan prodi lain
- [ ] Hanya bisa approve kegiatan yang sudah disetujui Pembina
- [ ] Test approve, revisi, tolak

### Pembina
- [ ] Login sebagai Pembina
- [ ] Verifikasi hanya melihat kegiatan prodi sendiri
- [ ] Hanya bisa approve kegiatan yang statusnya 'dikirim' atau 'revisi'
- [ ] Tidak bisa melihat draft
- [ ] Test approve, revisi, tolak

### Hima
- [ ] Login sebagai Hima
- [ ] Verifikasi hanya melihat kegiatan sendiri
- [ ] Tidak bisa melihat kegiatan hima lain
- [ ] Bisa create, edit, submit kegiatan
- [ ] Bisa upload file proposal/pendanaan/laporan
- [ ] Tidak ada tombol approve/revisi/tolak

---

**Tanggal Update:** 15 Desember 2025
**Status:** ✅ Semua akses role sudah dikonfigurasi dengan benar
