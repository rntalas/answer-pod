<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubjectController;

Route::resource('subject', SubjectController::class);

Route::resource('entry', EntryController::class);

Route::get('/{slug?}', [PageController::class, 'index'])
    ->where('slug', '.*')
    ->name('page.show');
