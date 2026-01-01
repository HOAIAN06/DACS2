<?php
// Test file to check if bootstrap works
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get the home view directly
$view = app('view');
$content = $view->make('home')->render();
echo "<pre>";
echo substr($content, 0, 1000);
echo "...</pre>";
echo "<hr>";
echo "Length: " . strlen($content);
?>
