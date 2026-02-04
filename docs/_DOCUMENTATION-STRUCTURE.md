# Struktur Dokumentasi SIPEKMA

**Panduan Organisasi Dokumentasi Proyek**

---

## 📋 Overview

Dokumentasi SIPEKMA diorganisir dengan prinsip **separation of concerns**: memisahkan **aturan AI** dari **dokumentasi project**.

---

## 🗂️ Struktur Dokumentasi

```
Web_SiPeKMa/
├── .github/
│   └── copilot-instructions.md        # ⚙️ AI RULES (untuk Copilot)
│
└── docs/                               # 📚 PROJECT DOCUMENTATION
    ├── README.md                       # Overview project
    ├── FILE-STRUCTURE.md               # Struktur file lengkap
    ├── CSS-SCSS-GUIDE.md              # CSS/SCSS guidelines
    ├── REFACTORING-LOG.md             # Change history
    ├── ARCHITECTURE.md                # Design patterns (planned)
    ├── DATABASE.md                    # Database schema (planned)
    ├── JAVASCRIPT-GUIDE.md            # JS patterns (planned)
    ├── API-ROUTES.md                  # Routes docs (planned)
    └── DEPLOYMENT.md                  # Deployment guide (planned)
```

---

## 🔀 Pemisahan: AI Rules vs Project Docs

### ⚙️ AI Rules - `.github/copilot-instructions.md`

**Tujuan:** Memberikan instruksi kepada GitHub Copilot tentang cara coding di project ini

**Audience:** GitHub Copilot AI Assistant

**Konten:**

| Section | Purpose |
|---------|---------|
| **⚠️ Aturan Penting** | Konfirmasi & summary requirements |
| **Gambaran Umum Proyek** | Project context untuk AI |
| **Stack Teknologi** | Tech stack yang digunakan |
| **Pola Arsitektur** | Architecture patterns (Menu, Toast, DataTables, Model) |
| **Konvensi Bahasa** | Indonesian vs English usage rules |
| **Alur Kerja Development** | Development commands & workflow |
| **Tugas Umum** | Common development tasks |
| **Referensi Lokasi File** | File locations |

**Karakteristik:**
- ✅ Spesifik untuk AI behavior
- ✅ Menggunakan format markdown dengan examples
- ✅ Fokus pada "how to code"
- ✅ Tidak perlu dipresentasikan ke pembimbing
- ✅ Living document (sering diupdate)

**Example Content:**

```markdown
## Pola Arsitektur

### Sistem Menu

Navigasi menggunakan **JSON-driven**, bukan berbasis route:

- Struktur menu berada di `resources/menu/verticalMenu.json`
- `MenuServiceProvider` memuat JSON dan membagikan `$menuData` ke semua view
- Template Blade di `resources/views/layouts/sections/menu/verticalMenu.blade.php` merender menu secara rekursif
```

---

### 📚 Project Documentation - `docs/`

**Tujuan:** Dokumentasi lengkap untuk developer, pembimbing skripsi, dan reviewer

**Audience:** Human readers (developer, dosen pembimbing, penguji)

**Konten:**

| Document | Purpose | Status |
|----------|---------|--------|
| **README.md** | Project overview & quick start | ✅ Complete (800 lines) |
| **FILE-STRUCTURE.md** | Complete file & folder structure | ✅ Complete (1,100 lines) |
| **CSS-SCSS-GUIDE.md** | CSS refactoring & best practices | ✅ Complete (1,200 lines) |
| **REFACTORING-LOG.md** | Detailed change history | ✅ Complete (600 lines) |
| **ARCHITECTURE.md** | System architecture & design | 🔄 In Progress |
| **DATABASE.md** | Database schema & ERD | 🔄 In Progress |
| **JAVASCRIPT-GUIDE.md** | Frontend JavaScript patterns | 🔄 In Progress |
| **API-ROUTES.md** | Routes documentation | 🔄 In Progress |
| **DEPLOYMENT.md** | Deployment procedures | ⏳ Planned |

**Karakteristik:**
- ✅ Formal & comprehensive
- ✅ PDF-ready format (untuk presentasi)
- ✅ Fokus pada "what & why"
- ✅ Harus dipresentasikan ke pembimbing
- ✅ Version-controlled (sesuai milestone)

**Example Content:**

```markdown
## 🚀 Quick Start

### Prerequisites

| Software | Version | Required |
|----------|---------|----------|
| PHP | 8.2+ | ✅ Wajib |
| Composer | 2.8+ | ✅ Wajib |
| Node.js | 24+ | ✅ Wajib |
```

---

## 🎯 Prinsip Pemisahan

### Kapan Menulis di AI Rules?

**Gunakan `.github/copilot-instructions.md` untuk:**

| Konten | Contoh |
|--------|--------|
| **Coding standards** | "Comments HARUS Bahasa Indonesia" |
| **Architecture patterns** | "Menu menggunakan JSON-driven" |
| **Common tasks** | "Cara menambah CRUD baru" |
| **File locations** | "Controllers di `app/Http/Controllers/`" |
| **Development workflow** | "Run `npm run dev` untuk hot reload" |
| **AI behavior rules** | "Konfirmasi sebelum perubahan besar" |
| **Naming conventions** | "Route names: `feature-name.action`" |

**✅ Karakteristik AI Rules:**
- Machine-readable instructions
- Fokus pada "bagaimana AI harus coding"
- Banyak code examples
- Format: Imperative ("WAJIB", "HARUS", "Gunakan")

### Kapan Menulis di Project Docs?

**Gunakan `docs/` untuk:**

| Konten | Contoh |
|--------|--------|
| **Project overview** | "SIPEKMA adalah sistem manajemen usulan kegiatan" |
| **Feature documentation** | "Workflow: Usulan → Proposal → Pendanaan → Laporan" |
| **Architecture explanation** | "Mengapa menggunakan MVC pattern" |
| **Technical decisions** | "Mengapa pilih Laravel 11 & MySQL" |
| **Setup guide** | "Installation step-by-step" |
| **API documentation** | "Endpoint list dengan parameters" |
| **Database schema** | "ERD dan relationship explanation" |
| **Deployment guide** | "How to deploy to production" |
| **Change history** | "Refactoring log dengan before/after" |

**✅ Karakteristik Project Docs:**
- Human-readable documentation
- Fokus pada "apa yang dibangun & mengapa"
- Formal & presentation-ready
- Format: Descriptive (explain, describe, showcase)

---

## 📊 Comparison Table

| Aspek | AI Rules | Project Docs |
|-------|----------|--------------|
| **Location** | `.github/copilot-instructions.md` | `docs/` folder |
| **Audience** | GitHub Copilot AI | Human (developer, dosen) |
| **Purpose** | Coding instructions | Project documentation |
| **Tone** | Imperative (command) | Descriptive (explain) |
| **Format** | Markdown with code examples | Formal documentation |
| **Update Frequency** | High (sering) | Medium (per milestone) |
| **Presentasi** | ❌ Tidak | ✅ Ya (ke pembimbing) |
| **PDF Export** | ❌ Tidak perlu | ✅ Harus bisa |
| **Version Control** | ✅ Git tracked | ✅ Git tracked |
| **Length** | ~750 lines | ~3,900+ lines (total) |

---

## 📝 Example: Same Topic, Different Treatment

### Topic: "CSS Refactoring"

#### ❌ AI Rules Version (copilot-instructions.md)

```markdown
## Custom SCSS Architecture

- Buat file di `resources/assets/scss/custom/`
- Gunakan prefix `sipekma-` untuk variables
- Import order: variables → components → utilities
- Hindari inline styles dan internal `<style>` blocks

**Contoh:**

```scss
// ✅ BENAR
$sipekma-primary: #696cff;
.bg-gradient-hero { background: $sipekma-gradient-primary; }

// ❌ SALAH
<div style="background: #696cff;">...</div>
```
```

**Fokus:** HOW to code (imperative, dengan ✅/❌)

---

#### ✅ Project Docs Version (docs/CSS-SCSS-GUIDE.md)

```markdown
## Refactoring Journey

### Fase 1: Audit & Planning

**Tanggal:** 02 Februari 2026

Kami melakukan audit menyeluruh terhadap codebase dan menemukan 50+ inline styles 
yang menghambat cacheability dan maintainability. Setelah analisis, kami memutuskan 
untuk membuat arsitektur SCSS custom dengan 3-file structure.

### Hasil Refactoring

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| Inline Styles | 53 | 0 | 🟢 100% reduction |
| Internal CSS | 94 lines | 0 | 🟢 100% reduction |
```

**Fokus:** WHAT was done & WHY (descriptive, dengan context)

---

## 🚀 Workflow: Menambah Dokumentasi Baru

### Step 1: Identifikasi Audience

| Question | Answer | Destination |
|----------|--------|-------------|
| Apakah ini aturan untuk AI? | Ya | `.github/copilot-instructions.md` |
| Apakah ini dokumentasi untuk manusia? | Ya | `docs/` |
| Apakah perlu dipresentasikan? | Ya | `docs/` |
| Apakah tentang "how to code"? | Ya | `.github/copilot-instructions.md` |
| Apakah tentang "what & why"? | Ya | `docs/` |

### Step 2: Pilih File yang Tepat

#### Untuk AI Rules:

- Semua dalam **1 file**: `.github/copilot-instructions.md`
- Update section yang relevan
- Tambah code examples

#### Untuk Project Docs:

| Topic | File |
|-------|------|
| **General overview** | `README.md` |
| **File structure** | `FILE-STRUCTURE.md` |
| **CSS/SCSS** | `CSS-SCSS-GUIDE.md` |
| **Database** | `DATABASE.md` |
| **JavaScript** | `JAVASCRIPT-GUIDE.md` |
| **Routes** | `API-ROUTES.md` |
| **Architecture** | `ARCHITECTURE.md` |
| **Deployment** | `DEPLOYMENT.md` |
| **Change history** | `REFACTORING-LOG.md` |

### Step 3: Format yang Konsisten

#### AI Rules Format:

```markdown
## Section Title

Brief explanation in Indonesian

**Pola:**
- Bullet point dengan code example

**Contoh:**

```language
// ✅ BENAR
good_code_example

// ❌ SALAH
bad_code_example
```

**Catatan:** Reminder atau warning
```

#### Project Docs Format:

```markdown
## Section Title

Detailed explanation dengan context dan reasoning.

### Subsection

| Table | Format | For | Data |
|-------|--------|-----|------|

**Code Example:**

```language
code_with_comments
```

**Result:** Explanation of outcome or benefit.
```

---

## 🎓 For Academic Review

### Dokumentasi untuk Skripsi

**Yang Perlu Dipresentasikan ke Dosen:**

| Document | Purpose | Priority |
|----------|---------|----------|
| `README.md` | Project overview | 🔴 High |
| `FILE-STRUCTURE.md` | Code organization | 🔴 High |
| `CSS-SCSS-GUIDE.md` | Technical decision showcase | 🔴 High |
| `DATABASE.md` | Database design | 🔴 High |
| `ARCHITECTURE.md` | System design | 🔴 High |
| `REFACTORING-LOG.md` | Development journey | 🟡 Medium |

**Yang TIDAK Perlu Dipresentasikan:**

| Document | Reasoning |
|----------|-----------|
| `copilot-instructions.md` | Internal AI rules, bukan deliverable akademik |

### Tips untuk Presentasi

1. **Export ke PDF**: Markdown → PDF dengan pandoc atau VS Code extension
2. **Highlight Metrics**: Tunjukkan improvement (before/after tables)
3. **Show Code Quality**: Contoh refactoring dengan impact analysis
4. **Explain Decisions**: Why chose Laravel, why hybrid language approach
5. **Demonstrate Results**: Screenshot + data tables

---

## 📦 Deliverables Checklist

### AI Rules Checklist

- [x] Aturan konfirmasi sebelum perubahan
- [x] Stack teknologi dijelaskan
- [x] Architecture patterns documented
- [x] Konvensi bahasa (Indonesian/English)
- [x] Development workflow
- [x] Common tasks guide
- [x] File location references

### Project Docs Checklist

#### ✅ Complete

- [x] README.md (800+ lines)
- [x] FILE-STRUCTURE.md (1,100+ lines)
- [x] CSS-SCSS-GUIDE.md (1,200+ lines)
- [x] REFACTORING-LOG.md (600+ lines)

#### 🔄 In Progress

- [ ] ARCHITECTURE.md
- [ ] DATABASE.md
- [ ] JAVASCRIPT-GUIDE.md
- [ ] API-ROUTES.md

#### ⏳ Planned

- [ ] DEPLOYMENT.md

---

## 🔗 Cross-Reference Strategy

### AI Rules → Project Docs

**Dalam copilot-instructions.md:**

```markdown
Untuk dokumentasi lengkap struktur file, lihat `docs/FILE-STRUCTURE.md`
```

### Project Docs → AI Rules

**Dalam docs/README.md:**

```markdown
> **Note for AI Development:** Coding standards dan AI instructions 
> tersedia di `.github/copilot-instructions.md`
```

### Inter-Docs Links

```markdown
Untuk detail database schema, lihat [DATABASE.md](DATABASE.md)
```

---

## 🛠️ Maintenance Guidelines

### Update Frequency

| Document Type | Frequency | Trigger |
|--------------|-----------|---------|
| **AI Rules** | Weekly | New pattern discovered |
| **README.md** | Monthly | Major feature added |
| **Technical Guides** | Per milestone | Architecture change |
| **REFACTORING-LOG** | Per refactoring | Code changes |

### Version Control

```bash
# Commit AI rules
git add .github/copilot-instructions.md
git commit -m "docs: update AI rules untuk CSS patterns"

# Commit project docs
git add docs/
git commit -m "docs: tambah CSS-SCSS-GUIDE.md (1,200 lines)"
```

### Review Process

| Phase | Action | Reviewer |
|-------|--------|----------|
| **Draft** | Write initial content | Developer |
| **Review** | Check accuracy & completeness | Peer/Senior |
| **Polish** | Format & proofread | Developer |
| **Approval** | Final check | Dosen Pembimbing |
| **Publish** | Merge to main | Developer |

---

## 📚 External Resources

### Documentation Tools

| Tool | Purpose | Link |
|------|---------|------|
| **Pandoc** | Markdown to PDF | https://pandoc.org |
| **Mermaid** | Diagrams in markdown | https://mermaid.js.org |
| **Draw.io** | ERD & architecture diagrams | https://draw.io |
| **Table Generator** | Markdown tables | https://tablesgenerator.com/markdown_tables |

### Best Practices

| Resource | Topic |
|----------|-------|
| **Write the Docs** | Documentation guide |
| **Google Developer Docs Style Guide** | Writing standards |
| **Divio Documentation System** | 4-type docs (tutorial, how-to, reference, explanation) |

---

## 🎯 Key Takeaways

### Summary

| Aspect | Key Point |
|--------|-----------|
| **Separation** | AI rules ≠ Project docs |
| **AI Rules** | How to code (imperative) |
| **Project Docs** | What & why (descriptive) |
| **Audience** | AI vs Human |
| **Presentasi** | Only project docs |
| **Format** | Commands vs Explanation |

### Remember

> **"AI rules tell the machine HOW to build.  
> Project docs tell humans WHAT was built and WHY."**

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Purpose:** Documentation structure guide  
**Status:** ✅ Reference Document
