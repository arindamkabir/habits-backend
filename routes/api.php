<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Habits\HabitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', LoginController::class);

Route::prefix('/habits')->name('habits.')->group(function () {
    Route::get('/', [HabitController::class, 'index'])->name('index');
    // Route::get('/all', [HabitController::class, 'allWithEntries'])->name('allWithEntries');
    // Route::post('/', [HabitController::class, 'store'])->name('store');
    // Route::post('/entries', [EntryController::class, 'save'])->name('entries.save');

    // Route::get('/{slug}', [HabitController::class, 'show'])->name('show');
    // Route::delete('/{id}', [HabitController::class, 'destroy'])->name('destroy');
});
