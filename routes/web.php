<?php

use App\Http\Controllers\AbsenciaController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FixatgeController;
use App\Http\Controllers\HorariController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TornController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Ruta principal: Mostra la pàgina de benvinguda
Route::get('/', function () {
    return view('welcome');
});

// Ruta del Dashboard: Requereix estar autenticat i haver verificat l'email
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup de rutes protegides pel middleware 'auth'
Route::middleware('auth')->group(function () {

    // Gestió del Perfil d'usuari
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- LES TEVES RUTES DE GESTIÓ (Resources) ---
    // Laravel crea automàticament les rutes per a index, create, store, show, edit, update i destroy
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('fixatges', FixatgeController::class);
    Route::resource('horaris', HorariController::class);
    Route::resource('absencies', AbsenciaController::class);
    Route::resource('torns', TornController::class);

    // --- RUTES PER A L'ELIMINACIÓ MASSIVA DE TORNS ---
    Route::get('/horaris-delete', [HorariController::class, 'delete'])->name('horaris.delete');
    Route::delete('/horaris-delete', [HorariController::class, 'destroyBatch'])->name('horaris.destroy-batch');

    // Ruta personalitzada per obtenir els esdeveniments de l'usuari (utilitzada per FullCalendar)
    Route::get('/api/horaris/user/{userId}', [HorariController::class, 'getEvents']);
});

// Carrega les rutes d'autenticació predefinides de Laravel Breeze (login, register, logout...)
require __DIR__.'/auth.php';
