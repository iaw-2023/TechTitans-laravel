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
Route::post('/categorias/store', [CategoriaControllerAPI:: class, 'store']);
Route::get('/categorias/show/{id}', [CategoriaControllerAPI:: class, 'show']);
Route::put('/categorias/update/{id}', [CategoriaControllerAPI:: class, 'update']);
Route::delete('/categorias/destroy/{id}', [CategoriaControllerAPI:: class, 'destroy']);

Route::get('/canchas', [CanchaControllerAPI::class, 'index']);
Route::post('/canchas/store', [CanchaControllerAPI::class, 'store']);
Route::get('/canchas/show/{id}', [CanchaControllerAPI::class, 'show']);
Route::put('/canchas/update/{id}', [CanchaControllerAPI::class, 'update']);
Route::delete('/canchas/destroy/{id}', [CanchaControllerAPI::class, 'destroy']);
Route::get('/canchas/categoria/{id_categoria}', [CanchaControllerAPI::class, 'buscarPorCategoria']);

Route::get('/turnos', [TurnoControllerAPI::class, 'index']);
Route::post('/turnos/store', [TurnoControllerAPI::class, 'store']);
Route::get('/turnos/show/{id}', [TurnoControllerAPI::class, 'show']);
Route::put('/turnos/update/{id}', [TurnoControllerAPI::class, 'update']);
Route::delete('/turnos/destroy/{id}', [TurnoControllerAPI::class, 'destroy']);
Route::get('/turnos/cancha/{id_cancha}', [TurnoControllerAPI::class, 'buscarPorCancha']);
Route::get('/turnos/fecha/{fecha_turno}', [TurnoControllerAPI::class, 'searchByDate']);
Route::get('/turnos/fecha-y-categoria/{fecha_turno}/{id_categoria}', [TurnoControllerAPI::class, 'searchByDateAndCategory']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
