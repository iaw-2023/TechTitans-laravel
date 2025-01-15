<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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
    Route::resource('categorias', CategoriaController::class);
    Route::resource('turnos', TurnoController::class);
    Route::resource('canchas', CanchaController::class);
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::get('/reservas/show/{id}', [ReservaController::class, 'show']);
    Route::get('/canchas/{id}', [CanchaController::class, 'show']);

    Route::get('/timezone-check', function () {
        return response()->json([
            'timezone' => date_default_timezone_get(),
            'datetime' => Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString(),
        ]);
    });
});

require __DIR__.'/auth.php';
