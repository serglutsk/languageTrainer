<?php

declare(strict_types=1);

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('lang', [LanguageController::class, 'index'])->middleware('admin')->name('lang.index');
    Route::get('dictionary', [DictionaryController::class, 'index'])->name('dictionary.index');
    Route::get('exercises', [ExerciseController::class, 'index'])->name('exercises.index');
});

require __DIR__.'/settings.php';
