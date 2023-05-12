<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TurnoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categoria.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categoria.store');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categoria.edit');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categoria.update');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');

    Route::resource('turnos', 'App\Http\Controllers\TurnoController');
    Route::resource('canchas', 'App\Http\Controllers\CanchaController');
    Route::resource('reservas', 'App\Http\Controllers\ReservaController');
});

require __DIR__.'/auth.php';