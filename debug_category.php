<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$cats = \App\Models\Category::where('name', 'like', '%Jean%')
    ->orWhere('slug', 'like', '%jean%')
    ->get();

echo "Found " . count($cats) . " categories:\n";
foreach($cats as $c) {
    echo $c->id . " | " . $c->name . " | " . $c->slug . " | " . $c->is_active . "\n";
}

// Also check the exact URL slug
echo "\n\nChecking for slug 'quan-jeans':\n";
$cat = \App\Models\Category::where('slug', 'quan-jeans')->first();
if ($cat) {
    echo "Found: " . $cat->name . " (active: " . $cat->is_active . ")\n";
} else {
    echo "Not found!\n";
}
?>
