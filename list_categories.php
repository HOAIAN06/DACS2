<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$categories = \App\Models\Category::where('is_active', 1)->get();

echo "Total active categories: " . count($categories) . "\n";
echo "================================\n";
foreach($categories as $cat) {
    echo "ID: " . $cat->id . " | Name: " . $cat->name . " | Slug: " . $cat->slug . "\n";
}
?>
