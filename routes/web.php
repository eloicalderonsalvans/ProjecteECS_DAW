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
    $user = auth()->user();
    $data = [];

    // Dies de vacances restants per a l'usuari actual
    $data['diesVacancesRestants'] = $user->diesVacancesRestants();
    $data['diesVacancesConsumits'] = $user->diesVacancesConsumits();
    $data['diesVacancesTotal'] = \App\Models\User::DIES_VACANCES_ANUALS;

    // Si l'usuari és admin, carreguem el comptador d'absències pendents
    if ($user->isAdmin()) {
        $data['absenciesPendents'] = \App\Models\Absencia::where('estat', 'pendent')->count();
    }

    return view('dashboard', $data);
})->middleware(['auth', 'verified'])->name('dashboard');

// =====================================================================
// RUTES ACCESSIBLES PER A TOTS ELS USUARIS AUTENTICATS
// =====================================================================
Route::middleware('auth')->group(function () {

    // Gestió del Perfil d'usuari
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- CALENDARI (tots els usuaris poden veure el calendari) ---
    Route::get('/horaris', [HorariController::class, 'index'])->name('horaris.index');
    Route::get('/api/horaris/user/{userId}', [HorariController::class, 'getEvents']);

    // --- API: obtenir dies de vacances d'un usuari (AJAX) ---
    Route::get('/api/vacances/user/{userId}', function ($userId) {
        $targetUser = \App\Models\User::findOrFail($userId);
        return response()->json([
            'consumits' => $targetUser->diesVacancesConsumits(),
            'restants' => $targetUser->diesVacancesRestants(),
            'total' => \App\Models\User::DIES_VACANCES_ANUALS,
        ]);
    });

    // --- ABSÈNCIES (funcionalitats d'usuari normal) ---
    Route::get('/absencies', [AbsenciaController::class, 'index'])->name('absencies.index');
    Route::get('/absencies/create', [AbsenciaController::class, 'create'])->name('absencies.create');
    Route::post('/absencies', [AbsenciaController::class, 'store'])->name('absencies.store');
    // L'usuari normal pot cancel·lar les seves pròpies absències pendents
    Route::delete('/absencies/{absencia}', [AbsenciaController::class, 'destroy'])->name('absencies.destroy');
});

// =====================================================================
// RUTES EXCLUSIVES PER A ADMINISTRADORS
// =====================================================================
Route::middleware(['auth', 'admin'])->group(function () {

    // --- Gestió completa d'Usuaris, Departaments i Torns ---
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('torns', TornController::class);

    // --- Gestió d'Horaris (assignar, crear, eliminar) ---
    Route::get('/horaris/create', [HorariController::class, 'create'])->name('horaris.create');
    Route::post('/horaris', [HorariController::class, 'store'])->name('horaris.store');
    Route::get('/horaris-delete', [HorariController::class, 'delete'])->name('horaris.delete');
    Route::delete('/horaris-delete', [HorariController::class, 'destroyBatch'])->name('horaris.destroy-batch');
    Route::delete('/horaris/{horari}', [HorariController::class, 'destroy'])->name('horaris.destroy');

    // --- Gestió d'Absències (editar, aprovar, rebutjar) ---
    Route::get('/absencies/{absencia}/edit', [AbsenciaController::class, 'edit'])->name('absencies.edit');
    Route::put('/absencies/{absencia}', [AbsenciaController::class, 'update'])->name('absencies.update');
    Route::patch('/absencies/{absencia}/aprovar', [AbsenciaController::class, 'aprovar'])->name('absencies.aprovar');
    Route::patch('/absencies/{absencia}/rebutjar', [AbsenciaController::class, 'rebutjar'])->name('absencies.rebutjar');
});

// Carrega les rutes d'autenticació predefinides de Laravel Breeze (login, register, logout...)
require __DIR__.'/auth.php';
