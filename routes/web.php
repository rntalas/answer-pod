<?php

use Illuminate\Support\Facades\Route;

$routes = require __DIR__ . '/routes.php';

foreach($routes as $url => $route) {
    Route::view($url, $route);
}
