<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$collections = DB::table('products')
    ->select('collection')
    ->distinct()
    ->whereNotNull('collection')
    ->get();

echo "Collections trong database:\n";
foreach($collections as $c) {
    echo "- '{$c->collection}'\n";
}

echo "\nSản phẩm Thu Đông:\n";
$products = DB::table('products')
    ->select('id', 'name', 'collection')
    ->whereRaw('LOWER(collection) LIKE ?', ['%thu%'])
    ->get();

foreach($products as $p) {
    echo "ID: {$p->id}, Name: {$p->name}, Collection: '{$p->collection}'\n";
}
