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
    Route::controller(HabitController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{slug}', 'show')->name('show');
        Route::put('/{slug}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/chart/{slug}', 'chart')->name('chart');
        Route::get('/pie-chart/{slug}', 'pieChart')->name('pie-chart');
    });

    Route::get('/{slug}/entries', [HabitEntryController::class, 'list'])->name('entries.list');
    Route::post('/entries', [HabitEntryController::class, 'save'])->name('entries.save');

    Route::prefix('/categories')->name('categories.')->controller(HabitCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });
});

Route::middleware(['auth:sanctum'])->prefix('/health')->name('health.')->group(function () {
    Route::prefix('/weight-entries')->name('weight-entries.')->controller(WeightEntryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/details', 'details')->name('details');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });
});
