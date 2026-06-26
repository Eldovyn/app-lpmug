$pattern = '\$this->translateTextCached\('
$replacement = "service('translation')->translateCached("
Get-ChildItem -Path "w:\laragon\www\CI4\lpmug\app\Controllers" -Filter '*.php' -File | ForEach-Object {
    $c = Get-Content $_.FullName -Raw
    if ($c -match $pattern) {
        $c = $c -replace $pattern, $replacement
        Set-Content -Path $_.FullName -Value $c -NoNewline
        Write-Host "Updated $($_.Name)"
    }
}
