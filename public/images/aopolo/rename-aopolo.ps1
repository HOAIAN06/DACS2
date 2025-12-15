$folder = "C:\xampp\htdocs\hanzo-shop\public\images\aopolo"

# Lấy tất cả file trong thư mục
Get-ChildItem -Path $folder -File | ForEach-Object {
    $oldName = $_.Name
    $oldPath = $_.FullName

    # Tách phần tên và số (1,2)
    if ($oldName -match "(.+?)(\d+)(\.[a-zA-Z0-9]+)$") {
        $productName = $matches[1].Trim()
        $index = $matches[2]
        $ext = $matches[3]

        # Chuyển tiếng Việt có dấu → không dấu + đổi khoảng trắng → dấu gạch ngang
        $normalized = $productName.Normalize([Text.NormalizationForm]::FormD)
        $clean = -join ($normalized.ToCharArray() | Where-Object { [Globalization.CharUnicodeInfo]::GetUnicodeCategory($_) -ne "NonSpacingMark" })
        $clean = $clean -replace "[^a-zA-Z0-9 ]", ""
        $clean = $clean.ToLower() -replace "\s+", "-"

        $newName = "$clean-$index$ext"
        $newPath = Join-Path $folder $newName

        Rename-Item -Path $oldPath -NewName $newName

        Write-Host "Renamed: $oldName → $newName"
    }
    else {
        Write-Host "Skipped: $oldName (không đúng định dạng có số 1,2)"
    }
}
