# ============================================
# SIPEKMA Documentation Converter
# Convert Markdown docs ke DOCX (Word)
# ============================================

param(
    [string]$Format = "docx"  # docx atau html
)

$pandocPath = "$env:LOCALAPPDATA\Pandoc\pandoc.exe"

# Cek Pandoc
if (!(Test-Path $pandocPath)) {
    Write-Host "Pandoc tidak ditemukan!" -ForegroundColor Red
    Write-Host "Jalankan: .\scripts\install-pandoc.ps1" -ForegroundColor Yellow
    exit 1
}

# Create output directory
if (!(Test-Path "docs\pdf")) {
    New-Item -ItemType Directory -Path "docs\pdf" -Force | Out-Null
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  SIPEKMA DOCS CONVERTER" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# List dokumentasi
$docs = @(
    @("README", "docs\README.md"),
    @("FILE-STRUCTURE", "docs\FILE-STRUCTURE.md"),
    @("CSS-SCSS-GUIDE", "docs\CSS-SCSS-GUIDE.md"),
    @("JAVASCRIPT-GUIDE", "docs\JAVASCRIPT-GUIDE.md"),
    @("DATABASE", "docs\DATABASE.md"),
    @("ARCHITECTURE", "docs\ARCHITECTURE.md"),
    @("API-ROUTES", "docs\API-ROUTES.md"),
    @("DEPLOYMENT", "docs\DEPLOYMENT.md"),
    @("REFACTORING-LOG", "docs\REFACTORING-LOG.md")
)

$success = 0
$failed = 0
$i = 1
$total = $docs.Count

foreach ($doc in $docs) {
    $name = $doc[0]
    $input = $doc[1]
    $output = "docs\pdf\{0:D2}-{1}.{2}" -f $i, $name, $Format
    
    Write-Host "$i/$total Converting $name..." -ForegroundColor Yellow -NoNewline
    
    if (!(Test-Path $input)) {
        Write-Host " File not found!" -ForegroundColor Red
        $failed++
        $i++
        continue
    }
    
    # Convert based on format
    if ($Format -eq "docx") {
        & $pandocPath $input -o $output `
            --toc `
            --toc-depth=3 `
            --number-sections `
            --metadata title="SIPEKMA - $name" `
            --metadata author="Johanda Putra" `
            --metadata date="$(Get-Date -Format 'yyyy-MM-dd')" `
            2>&1 | Out-Null
    }
    elseif ($Format -eq "html") {
        & $pandocPath $input -o $output `
            -t html5 `
            --standalone `
            --self-contained `
            --toc `
            --toc-depth=3 `
            --metadata title="SIPEKMA - $name" `
            --css-variable="body{font-family:Calibri,sans-serif}" `
            2>&1 | Out-Null
    }
    
    if (Test-Path $output) {
        $size = [math]::Round((Get-Item $output).Length / 1KB, 2)
        Write-Host " Done! ($size KB)" -ForegroundColor Green
        $success++
    } else {
        Write-Host " Failed" -ForegroundColor Red
        $failed++
    }
    
    $i++
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Success: $success files" -ForegroundColor Green
if ($failed -gt 0) {
    Write-Host "Failed:  $failed files" -ForegroundColor Red
}
Write-Host "Output:  docs\pdf\" -ForegroundColor Cyan
Write-Host "Format:  .$Format" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Open folder
Write-Host "Opening output folder..." -ForegroundColor Yellow
Start-Process explorer.exe -ArgumentList (Resolve-Path "docs\pdf")
