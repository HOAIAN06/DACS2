# ===============================
# CONFIG
# ===============================
$folder      = 'C:\xampp\htdocs\hanzo-shop\public\images\aosomi'
$outProducts = Join-Path $folder 'insert-aosomi-products.sql'
$outImages   = Join-Path $folder 'insert-aosomi-images.sql'

# category_id của Áo Sơ Mi trong bảng categories
$catId = 20   # 👉 sửa lại cho đúng category_id áo sơ mi

# Xoá file cũ nếu có
if (Test-Path $outProducts) { Remove-Item $outProducts }
if (Test-Path $outImages)   { Remove-Item $outImages }

Set-Location $folder
Write-Host ("Scan folder: {0}" -f $folder)

# ===============================
# wordMap: map từng TỪ trong slug -> tiếng Việt có dấu
# Ví dụ: "ao so mi nam tay ngan" -> "Áo Sơ Mi Nam Tay Ngắn"
# ===============================
$wordMap = @{
    "ao"      = "Áo"
    "so"      = "Sơ"
    "mi"      = "Mi"
    "nam"     = "Nam"
    "nu"      = "Nữ"
    "tay"     = "Tay"
    "ngan"    = "Ngắn"
    "dai"     = "Dài"
    "co"      = "Cổ"
    "co-dung" = "Cổ Đứng"
    "soc"     = "Sọc"
    "ke"      = "Kẻ"
    "hoa"     = "Hoạ"
    "tiet"    = "Tiết"
    "theu"    = "Thêu"
    "vai"     = "Vải"
    "soi"     = "Sợi"
    "det"     = "Dệt"
    "nhe"     = "Nhẹ"
    "moc"     = "Móc"
    "caro"    = "Caro"
    "flanel"  = "Flanel"
    "chong"   = "Chống"
    "nhan"    = "Nhăn"
    "nan"     = "Nắn"
    "nano"    = "Nano"
    "cotton"  = "Cotton"
    "kaki"    = "Kaki"
    "linen"   = "Linen"
    "oxford"  = "Oxford"
    "poly"    = "Poly"
    "spandex" = "Spandex"
}

# ===============================
# (Tùy chọn) slugNameMap: nếu có slug đặc biệt cần tên khác hẳn
# để trống cũng được, sau này bạn thích thì thêm vào.
# ===============================
$slugNameMap = @{
    # Ví dụ:
    # "ao-so-mi-nam-cuban-ethnic-classical-motif" = "Áo Sơ Mi Nam Cuban Ethnic Classical Motif"
}

# ===============================
# Hàm: chuyển slug -> tên tiếng Việt có dấu
# ===============================
function Get-VietnameseNameFromSlug([string]$slug) {

    $slugLower = $slug.ToLower()

    # Nếu slug nằm trong slugNameMap thì ưu tiên dùng
    if ($slugNameMap.ContainsKey($slugLower)) {
        return $slugNameMap[$slugLower]
    }

    $parts = $slugLower -split '-'
    $parts = $parts | Where-Object { $_ -ne "" }

    $ti = (Get-Culture).TextInfo
    $words = New-Object System.Collections.Generic.List[string]

    foreach ($p in $parts) {
        if ($wordMap.ContainsKey($p)) {
            $words.Add($wordMap[$p])
        }
        else {
            # Từ tiếng Anh hoặc từ không có trong wordMap -> TitleCase
            $words.Add($ti.ToTitleCase($p))
        }
    }

    return ($words -join ' ')
}

# ===============================
# Gom nhóm hình theo slug
# ===============================
$groups = @{}

Get-ChildItem -Path . -File | Where-Object {
    $_.Extension -in @('.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG')
} | ForEach-Object {

    $name = $_.BaseName   # "ao-so-mi-nam-...-1"

    if ($name -match '^(.*?)-(\d+)$') {
        $slug  = $matches[1]          # "ao-so-mi-nam-..."
        $index = [int]$matches[2]     # 1, 2
    }
    else {
        $slug  = $name
        $index = 1
    }

    if (-not $groups.ContainsKey($slug)) {
        $groups[$slug] = @()
    }

    $groups[$slug] += [PSCustomObject]@{
        File  = $_
        Index = $index
    }
}

Write-Host ("Total slugs: {0}" -f $groups.Count)

if ($groups.Count -eq 0) {
    Write-Host 'No images found. Stop.'
    return
}

# ===============================
# Tạo INSERT cho bảng products
# ===============================
foreach ($slug in $groups.Keys) {

    $name = Get-VietnameseNameFromSlug $slug
    $desc = "Mô tả cho $name"
    $price = 299000   # giá đề xuất cho áo sơ mi, cần thì chỉnh lại

    # escape dấu '
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
    $group    = $groups[$slug]

    foreach ($img in $group) {
        $url    = "/images/aosomi/$($img.File.Name)"
        $urlSql = $url.Replace("'", "''")
        $isMain = if ($img.Index -eq 1) { 1 } else { 0 }

        $sql = "INSERT INTO product_images (product_id, image_url, is_main) " +
               "SELECT id, '$urlSql', $isMain FROM products WHERE slug = '$safeSlug';"

        Add-Content -Path $outImages -Value $sql
    }
}

Write-Host 'Done generating SQL for ÁO SƠ MI.'
Write-Host ("Products file: {0}" -f $outProducts)
Write-Host ("Images file:   {0}" -f $outImages)
