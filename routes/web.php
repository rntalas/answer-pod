<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UnitController;

Route::resource('subject', SubjectController::class);

Route::get('unit/{id}', [UnitController::class, 'show'])
    ->name('unit.show');

Route::resource('entry', EntryController::class);

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/{slug}', [PageController::class, 'index'])
    ->where('slug', '.*')
    ->name('page.show');
