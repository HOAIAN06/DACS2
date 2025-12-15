# ===============================
# CONFIG
# ===============================
$folder      = 'C:\xampp\htdocs\hanzo-shop\public\images\Mickey&Friends'
$outProducts = Join-Path $folder 'insert-Mickey&Friends-products.sql'
$outImages   = Join-Path $folder 'insert-Mickey&Friends-images.sql'

# Map prefix file -> category_id
$prefixMap = @{
    "ao-thun"       = 18   # Áo thun
    "ao-so-mi"      = 19   # Áo sơ mi
    "quan-short"    = 27   # Quần short
    "quan-jean"     = 26   # Quần jean
    "tank-top"      = 24   # Tanktop
    "tui-tote"      = 33   # Túi / ví
    "mu-luoi-trai"  = 32   # Mũ
}

function Get-CategoryId([string]$fileName) {
    foreach ($prefix in $prefixMap.Keys) {
        if ($fileName -like "$prefix*") {
            return $prefixMap[$prefix]
        }
    }
    return $null
}

# Xoá file cũ nếu có
if (Test-Path $outProducts) { Remove-Item $outProducts }
if (Test-Path $outImages)   { Remove-Item $outImages }

Set-Location $folder
Write-Host ("Scan folder: {0}" -f $folder)

# ===============================
# Gom nhóm hình theo slug
# ===============================
$groups = @{}

Get-ChildItem -Path . -File | Where-Object {
    $_.Extension -in @('.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG')
} | ForEach-Object {

    $name = $_.BaseName   # tên không có .jpg

    # tách slug + index (ao-thun-nam-abc-1)
    if ($name -match '^(.*?)-(\d+)$') {
        $slug  = $matches[1]
        $index = [int]$matches[2]
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

    # Xác định category theo slug (prefix)
    $catId = Get-CategoryId $slug
    if (-not $catId) {
        Write-Host "❌ Không xác định được category cho slug: $slug  -> bỏ qua"
        continue
    }

    # "ao-thun-nam-peanuts-infinite" -> "Ao thun nam peanuts infinite"
    $rawName = $slug -replace '-', ' '
    if ($rawName.Length -gt 1) {
        $name = $rawName.Substring(0,1).ToUpper() + $rawName.Substring(1)
    } else {
        $name = $rawName
    }

    $desc  = "Mo ta cho $name"
    $price = 199000

    # escape dấu ' trong SQL
    $safeName       = $name.Replace("'", "''")
    $safeSlug       = $slug.Replace("'", "''")
    $safeDesc       = $desc.Replace("'", "''")
    $safeCollection = "Mickey&Friends"

    $sql = "INSERT INTO products (name, slug, description, price, category_id, collection) " +
           "VALUES ('$safeName', '$safeSlug', '$safeDesc', $price, $catId, '$safeCollection');"

    Add-Content -Path $outProducts -Value $sql
}

# ===============================
# Tạo INSERT cho bảng product_images
# ===============================
foreach ($slug in $groups.Keys) {

    $safeSlug = $slug.Replace("'", "''")
    $group    = $groups[$slug]

    foreach ($img in $group) {
        $url    = "/images/Mickey&Friends/$($img.File.Name)"
        $urlSql = $url.Replace("'", "''")
        $isMain = if ($img.Index -eq 1) { 1 } else { 0 }

        $sql = "INSERT INTO product_images (product_id, image_url, is_main) " +
               "SELECT id, '$urlSql', $isMain FROM products WHERE slug = '$safeSlug';"

        Add-Content -Path $outImages -Value $sql
    }
}

Write-Host '✅ Done generating SQL for Mickey&Friends.'
Write-Host ("Products file: {0}" -f $outProducts)
Write-Host ("Images file:   {0}" -f $outImages)
