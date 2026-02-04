Write-Host "========================================"  -ForegroundColor Cyan
Write-Host "  PANDOC INSTALLATION SCRIPT" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$pandocVersion = "3.1.11.1"
$downloadUrl = "https://github.com/jgm/pandoc/releases/download/$pandocVersion/pandoc-$pandocVersion-windows-x86_64.zip"
$downloadPath = "$env:TEMP\pandoc.zip"
$extractPath = "$env:TEMP\pandoc"
$installPath = "$env:LOCALAPPDATA\Pandoc"

Write-Host "Download Pandoc $pandocVersion..." -ForegroundColor Yellow
Invoke-WebRequest -Uri $downloadUrl -OutFile $downloadPath -UseBasicParsing
Write-Host "Download complete!" -ForegroundColor Green

Write-Host "Extracting files..." -ForegroundColor Yellow
Expand-Archive -Path $downloadPath -DestinationPath $extractPath -Force

if (!(Test-Path $installPath)) {
    New-Item -ItemType Directory -Path $installPath -Force | Out-Null
}

Write-Host "Installing to: $installPath" -ForegroundColor Yellow
Copy-Item -Path "$extractPath\pandoc-$pandocVersion\*" -Destination $installPath -Recurse -Force

$currentPath = [Environment]::GetEnvironmentVariable("Path", "User")
if ($currentPath -notlike "*$installPath*") {
    Write-Host "Adding to PATH..." -ForegroundColor Yellow
    [Environment]::SetEnvironmentVariable("Path", "$currentPath;$installPath", "User")
    $env:Path += ";$installPath"
}

Write-Host ""
Write-Host "Pandoc berhasil diinstall!" -ForegroundColor Green
Write-Host "Location: $installPath" -ForegroundColor Cyan

Remove-Item $downloadPath -Force -ErrorAction SilentlyContinue
Remove-Item $extractPath -Recurse -Force -ErrorAction SilentlyContinue

Write-Host ""
Write-Host "Testing Pandoc..." -ForegroundColor Yellow
& "$installPath\pandoc.exe" --version
Write-Host ""
Write-Host "INSTALLATION COMPLETE!" -ForegroundColor Green
