<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirige la página principal al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Todas las rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard -> listado de tareas
    Route::get('/dashboard', [TareaController::class, 'index'])
        ->name('dashboard');

    // CRUD de tareas
    Route::resource('tareas', TareaController::class);

});

// Perfil de Breeze
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';