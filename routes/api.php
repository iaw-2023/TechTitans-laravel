<?php

use App\Http\Controllers\API\CanchaControllerAPI;
use App\Http\Controllers\API\TurnoControllerAPI;
use App\Http\Controllers\API\CategoriaControllerAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/categorias', [CategoriaControllerAPI:: class, 'index']);
Route::get('/categorias/show/{id}', [CategoriaControllerAPI:: class, 'show']);

Route::get('/canchas', [CanchaControllerAPI::class, 'index']);
Route::get('/canchas/show/{id}', [CanchaControllerAPI::class, 'show']);
Route::get('/canchas/categoria/{id_categoria}', [CanchaControllerAPI::class, 'buscarPorCategoria']);

Route::get('/turnos', [TurnoControllerAPI::class, 'index']);
Route::get('/turnos/show/{id}', [TurnoControllerAPI::class, 'show']);
Route::get('/turnos/cancha/{id_cancha}', [TurnoControllerAPI::class, 'buscarPorCancha']);
Route::get('/turnos/fecha/{fecha_turno}', [TurnoControllerAPI::class, 'searchByDate']);
Route::get('/turnos/fecha/categoria/{fecha_turno}/{id_categoria}', [TurnoControllerAPI::class, 'searchByDateAndCategory']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
