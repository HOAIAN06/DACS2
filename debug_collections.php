<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Check all collections
$collections = \App\Models\Product::select('collection')
    ->where('is_active', 1)
    ->distinct()
    ->get();

echo "Active collections:\n";
foreach($collections as $item) {
    $count = \App\Models\Product::where('collection', $item->collection)
        ->where('is_active', 1)
        ->count();
    echo "  - '{$item->collection}': {$count} products\n";
}

// Check retro-sports specifically
echo "\nChecking 'retro-sports':\n";
$count = \App\Models\Product::where('collection', 'retro-sports')->count();
echo "  Total: {$count}\n";
$activeCount = \App\Models\Product::where('collection', 'retro-sports')->where('is_active', 1)->count();
echo "  Active: {$activeCount}\n";

// List them
$products = \App\Models\Product::where('collection', 'retro-sports')->get();
foreach($products as $p) {
    echo "    - {$p->name} (id: {$p->id}, active: {$p->is_active})\n";
}
?>
