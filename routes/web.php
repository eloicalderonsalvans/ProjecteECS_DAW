<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsenciaController;
use App\Http\Controllers\HorariController;
use App\Http\Controllers\FixatgeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TornController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- LES TEVES RUTES DE GESTIÓ ---
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('fixatges', FixatgeController::class);
    Route::resource('horaris', HorariController::class);
    Route::resource('absencies', AbsenciaController::class);
    Route::resource('torns', TornController::class);
});

require __DIR__.'/auth.php';
