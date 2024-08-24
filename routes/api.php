<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Habits\HabitCategoryController;
use App\Http\Controllers\Habits\HabitController;
use App\Http\Controllers\Habits\HabitEntryController;
use App\Http\Controllers\Health\WaterEntryController;
use App\Http\Controllers\Health\WeightEntryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', LoginController::class);

Route::middleware(['auth:sanctum'])->prefix('/habits')->name('habits.')->group(function () {
    Route::prefix('/entries')->name('entries.')->controller(HabitEntryController::class)->group(function () {
        Route::post('/', 'save')->name('save');
        Route::get('/{slug}', 'list')->name('list');
    });

    Route::prefix('/categories')->name('categories.')->controller(HabitCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });

    Route::controller(HabitController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{slug}', 'show')->name('show');
        Route::put('/{slug}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/chart/{slug}', 'chart')->name('chart');
        Route::get('/pie-chart/{slug}', 'pieChart')->name('pie-chart');
    });
});

Route::middleware(['auth:sanctum'])->prefix('/health')->name('health.')->group(function () {
    Route::prefix('/weight')->name('weight.')->controller(WeightEntryController::class)->group(function () {
        Route::get('/chart', 'chart')->name('chart');
        Route::post('/entries', 'save')->name('entries.save');
        Route::get('/entries/{date}', 'show')->name('entries.show');
        Route::delete('/{id}', 'delete')->name('entries.delete');
    });

    Route::prefix('/water')->name('water.')->controller(WaterEntryController::class)->group(function () {
        Route::get('/chart', 'chart')->name('chart');
        Route::post('/entries', 'save')->name('entries.save');
        Route::get('/entries/{date}', 'show')->name('entries.show');
        Route::delete('/{id}', 'delete')->name('entries.delete');
    });
});
