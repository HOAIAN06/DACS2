# === CONFIG: đường dẫn thư mục ảnh aothun ===
$folder = "C:\xampp\htdocs\hanzo-shop\public\images\non"

Set-Location $folder

# Hàm convert tiếng Việt -> slug không dấu
function To-Slug([string]$text) {
    $normalized = $text.Normalize([Text.NormalizationForm]::FormD)
    $sb = New-Object System.Text.StringBuilder

    foreach ($ch in $normalized.ToCharArray()) {
        $uc = [Globalization.CharUnicodeInfo]::GetUnicodeCategory($ch)
        if ($uc -ne [Globalization.UnicodeCategory]::NonSpacingMark) {
            [void]$sb.Append($ch)
        }
    }

    $noDiacritics = $sb.ToString().Normalize([Text.NormalizationForm]::FormC)
    $slug = $noDiacritics.ToLower()

    $slug = -join ($slug.ToCharArray() | ForEach-Object {
        if ($_ -match '[a-z0-9]') { $_ }
        else { '-' }
    })

    $slug = $slug -replace '-+', '-'
    $slug = $slug.Trim('-')
    return $slug
}

# Đổi tên tất cả file ảnh trong thư mục
Get-ChildItem -File *.jpg,*.jpeg,*.png | ForEach-Object {

    $name = $_.BaseName

    if ($name -match '^(.*?)(\d+)$') {
        $title = $matches[1].Trim()
        $index = $matches[2]
    } else {
        $title = $name
        $index = '1'
    }

    $slug = To-Slug $title
    $newName = "{0}-{1}{2}" -f $slug, $index, $_.Extension.ToLower()

    if ($_.Name -ne $newName) {
        Write-Host "$($_.Name)  -->  $newName"
        Rename-Item $_.FullName $newName
    }
}

