# ===============================
# CONFIG
# ===============================
$folder      = 'C:\xampp\htdocs\hanzo-shop\public\images\quanjeans'
$outProducts = Join-Path $folder 'insert-quanjeans-products.sql'
$outImages   = Join-Path $folder 'insert-quanjeans-images.sql'

# category_id của Quần Jean trong bảng categories
$catId = 26   # sửa lại cho đúng id quần jean trong DB

# Xoá file cũ nếu có
if (Test-Path $outProducts) { Remove-Item $outProducts }
if (Test-Path $outImages)   { Remove-Item $outImages }

Set-Location $folder
Write-Host ("Scan folder: {0}" -f $folder)

# ===============================
# Hàm convert tiếng Việt -> slug không dấu
# ===============================
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

    # đổi đ/Đ -> d/D bằng mã char để tránh lỗi encoding
    $noDiacritics = $noDiacritics -replace [char]273,'d' -replace [char]272,'D'

    $slug = $noDiacritics.ToLower()

    # chỉ giữ a-z, 0-9, còn lại thành '-'
    $slug = -join ($slug.ToCharArray() | ForEach-Object {
        if ($_ -match '[a-z0-9]') { $_ } else { '-' }
    })

    # gộp '-' và bỏ '-' đầu/cuối
    $slug = $slug -replace '-+', '-'
    return $slug.Trim('-')
}

# ===============================
# Gom nhóm hình: name = phần trước 1/2, slug = To-Slug(name)
# ===============================
$groups = @{}

Get-ChildItem -Path . -File | Where-Object {
    $_.Extension -in @('.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG')
} | ForEach-Object {

    $base = $_.BaseName   # VD: "Quần Jean Nam Blue Sand Ống Suông Vintage Wash1"

    # tách: "Quần Jean Nam ..." + số 1/2 ở cuối
    if ($base -match '^(.*?)(\d+)$') {
        $title = $matches[1].Trim()
        $index = [int]$matches[2]
    }
    else {
        $title = $base
        $index = 1
    }

    $slug = To-Slug $title

    if (-not $groups.ContainsKey($slug)) {
        $groups[$slug] = @{
            Name   = $title   # giữ nguyên tên có dấu
            Images = @()
        }
    }

    $groups[$slug].Images += [PSCustomObject]@{
        File  = $_
        Index = $index
    }
}

Write-Host ("Total products (slugs): {0}" -f $groups.Count)

if ($groups.Count -eq 0) {
    Write-Host 'No images found. Stop.'
    return
}

# ===============================
# Tạo INSERT cho bảng products
# ===============================
foreach ($slug in $groups.Keys) {

    $name  = $groups[$slug].Name        # tên có dấu
    $desc  = "Mô tả cho $name"
    $price = 299000

    $safeName = $name.Replace("'", "''")
    $safeSlug = $slug.Replace("'", "''")
    $safeDesc = $desc.Replace("'", "''")

    $sql = "INSERT INTO products (name, slug, description, price, category_id) " +
           "VALUES ('$safeName', '$safeSlug', '$safeDesc', $price, $catId);"

    Add-Content -Path $outProducts -Value $sql
}

# ===============================
# Tạo INSERT cho bảng product_images
# ===============================
foreach ($slug in $groups.Keys) {

    $safeSlug = $slug.Replace("'", "''")
    $images   = $groups[$slug].Images

    foreach ($img in $images) {
        $url    = "/images/quanjeans/$($img.File.Name)"
        $urlSql = $url.Replace("'", "''")
        $isMain = if ($img.Index -eq 1) { 1 } else { 0 }

        $sql = "INSERT INTO product_images (product_id, image_url, is_main) " +
               "SELECT id, '$urlSql', $isMain FROM products WHERE slug = '$safeSlug';"

        Add-Content -Path $outImages -Value $sql
    }
}

Write-Host 'Done generating SQL for QUAN JEAN.'
Write-Host ("Products file: {0}" -f $outProducts)
Write-Host ("Images file:   {0}" -f $outImages)
