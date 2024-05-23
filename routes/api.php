<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Habits\HabitCategoryController;
use App\Http\Controllers\Habits\HabitController;
use App\Http\Controllers\Habits\HabitEntryController;
use App\Http\Controllers\Health\WeightEntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', LoginController::class);

Route::middleware(['auth:sanctum'])->prefix('/habits')->name('habits.')->group(function () {
    Route::get('/', [HabitController::class, 'index'])->name('index');
    Route::post('/', [HabitController::class, 'store'])->name('store');

    Route::post('/entries', [HabitEntryController::class, 'save'])->name('entries.save');

    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [HabitCategoryController::class, 'index'])->name('index');
        Route::post('/', [HabitCategoryController::class, 'store'])->name('store');
    });

    Route::get('/{slug}', [HabitController::class, 'show'])->name('show');
    Route::put('/{slug}', [HabitController::class, 'update'])->name('update');
    Route::delete('/{id}', [HabitController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth:sanctum'])->prefix('/health')->name('health.')->group(function () {
    Route::prefix('/weight-entries')->name('weight-entries.')->group(function () {
        Route::get('/', [WeightEntryController::class, 'index'])->name('index');
        Route::post('/', [WeightEntryController::class, 'store'])->name('store');
        Route::put('/{id}', [WeightEntryController::class, 'update'])->name('update');
        Route::delete('/{id}', [WeightEntryController::class, 'delete'])->name('delete');
    });
});
