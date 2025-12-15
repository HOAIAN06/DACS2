# =========================================
# RENAME AOSOMI IMAGES TO SLUG FORMAT
# Kết quả: ao-so-mi-nam-cuban-ethnic-classical-motif-1.jpg
# =========================================

# Thư mục chứa ảnh áo sơ mi
$folder = "C:\xampp\htdocs\hanzo-shop\public\images\aosomi"

Set-Location $folder

# Hàm convert tiếng Việt -> slug không dấu
function To-Slug([string]$text) {
    # Bỏ dấu
    $normalized = $text.Normalize([Text.NormalizationForm]::FormD)
    $chars = $normalized.ToCharArray() | Where-Object {
        [Globalization.CharUnicodeInfo]::GetUnicodeCategory($_) -ne
        [Globalization.UnicodeCategory]::NonSpacingMark
    }
    $noDiacritics = -join $chars

    # về lowercase
    $slug = $noDiacritics.ToLower()

    # giữ a-z0-9, còn lại thay bằng '-'
    $slug = -join ($slug.ToCharArray() | ForEach-Object {
        if ($_ -match '[a-z0-9]') { $_ } else { '-' }
    })

    # gom nhiều dấu '-' thành 1, bỏ '-' ở đầu/cuối
    $slug = $slug -replace '-+', '-'
    $slug = $slug.Trim('-')

    return $slug
}

# Đổi tên tất cả file ảnh trong thư mục
Get-ChildItem -Path $folder -File |
Where-Object { $_.Extension -in '.jpg','.jpeg','.png','.JPG','.JPEG','.PNG' } |
ForEach-Object {

    $baseName = $_.BaseName   # không gồm .jpg

    # Tách "tên sản phẩm" + số thứ tự (1,2)
    if ($baseName -match '^(.*?)(\d+)$') {
        $title = $matches[1].Trim()
        $index = $matches[2]
    }
    else {
        # nếu không có số ở cuối thì coi như "1"
        $title = $baseName
        $index = '1'
    }

    $slug = To-Slug $title
    $newName = "{0}-{1}{2}" -f $slug, $index, $_.Extension.ToLower()

    if ($_.Name -ne $newName) {
        Write-Host "$($_.Name)  -->  $newName"
        Rename-Item -Path $_.FullName -NewName $newName
    }
}
