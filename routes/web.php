<?php

use App\Http\Controllers\PageController;

Route::get('/{slug?}', [PageController::class, 'index'])
    ->where('slug', '.*')
    ->name('page.show');
