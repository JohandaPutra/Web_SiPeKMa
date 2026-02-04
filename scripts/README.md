# Scripts Documentation

Folder ini berisi automation scripts untuk project SIPEKMA.

## 📜 Available Scripts

### 1. install-pandoc.ps1
Install Pandoc document converter secara otomatis.

**Usage:**
```powershell
.\scripts\install-pandoc.ps1
```

**What it does:**
- Download Pandoc versi terbaru
- Install ke `%LOCALAPPDATA%\Pandoc`
- Add ke PATH (user level, tidak perlu admin)
- Verify installation

**Requirements:**
- Windows PowerShell
- Internet connection
- ~50MB disk space

---

### 2. convert-docs.ps1
Convert semua dokumentasi Markdown ke format Word (.docx) atau HTML.

**Usage:**
```powershell
# Convert ke DOCX (default)
.\scripts\convert-docs.ps1

# Convert ke HTML
.\scripts\convert-docs.ps1 -Format html
```

**What it does:**
- Convert 9 dokumentasi files
- Generate table of contents
- Add numbering ke sections
- Add metadata (author, date, title)
- Output ke `docs\pdf\` folder

**Output Files:**
- 01-README.docx (atau .html)
- 02-FILE-STRUCTURE.docx
- 03-CSS-SCSS-GUIDE.docx
- 04-JAVASCRIPT-GUIDE.docx
- 05-DATABASE.docx
- 06-ARCHITECTURE.docx
- 07-API-ROUTES.docx
- 08-DEPLOYMENT.docx
- 09-REFACTORING-LOG.docx

**Requirements:**
- Pandoc harus sudah terinstall
- PowerShell Execution Policy allow scripts

---

## 🚀 Quick Start

### First Time Setup:
```powershell
# 1. Install Pandoc
.\scripts\install-pandoc.ps1

# 2. Restart terminal
# (close & open PowerShell baru)

# 3. Convert docs
.\scripts\convert-docs.ps1
```

### Update Documentation:
Setiap kali update file `.md`, tinggal jalankan:
```powershell
.\scripts\convert-docs.ps1
```

---

## 📝 Notes

### Execution Policy
Jika error "cannot be loaded because running scripts is disabled":
```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
```

### Output Folder
Semua hasil konversi ada di:
```
docs/pdf/
├── 01-README.docx
├── 02-FILE-STRUCTURE.docx
├── ... (dll)
```

### Format Comparison

| Format | Pros | Cons | Use Case |
|--------|------|------|----------|
| **DOCX** | ✅ Editable di Word<br>✅ Bisa print langsung<br>✅ File size kecil | ❌ Perlu MS Word | Untuk dosen, sidang, print |
| **HTML** | ✅ View di browser<br>✅ Hyperlinks clickable<br>✅ Universal | ❌ Susah print<br>❌ File size besar | Untuk online reading |

### File Naming Convention
Files dinomori untuk sorting:
- `01-` = Overview (README)
- `02-` = Structure
- `03-04-` = Frontend (CSS, JS)
- `05-06-` = Backend (DB, Architecture)
- `07-` = API Reference
- `08-` = Deployment
- `09-` = History (Refactoring)

---

## 🔧 Troubleshooting

### "Pandoc tidak ditemukan"
```powershell
# Re-install Pandoc
.\scripts\install-pandoc.ps1

# Atau cek PATH manual
$env:Path -split ';' | Select-String "Pandoc"
```

### "File not found"
Pastikan semua dokumentasi ada di folder `docs/`:
```powershell
Get-ChildItem docs\*.md
```

### Konversi gagal
- Cek syntax Markdown (tidak ada karakter aneh)
- Cek encoding file (harus UTF-8)
- Restart terminal setelah install Pandoc

---

## 📚 Additional Resources

- **Pandoc Manual**: https://pandoc.org/MANUAL.html
- **Markdown Guide**: https://www.markdownguide.org/
- **PowerShell Docs**: https://docs.microsoft.com/powershell/

---

**Last Updated:** 2026-02-02  
**Maintainer:** Johanda Putra
