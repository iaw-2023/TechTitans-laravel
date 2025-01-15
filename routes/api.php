<?php

use App\Http\Controllers\API\CanchaControllerAPI;
use App\Http\Controllers\API\TurnoControllerAPI;
use App\Http\Controllers\API\CategoriaControllerAPI;
use App\Http\Controllers\API\ReservaControllerAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MercadoPagoAPIController;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categorias', [CategoriaControllerAPI:: class, 'index']);
Route::get('/categorias/{id}', [CategoriaControllerAPI:: class, 'show']);

Route::get('/canchas', [CanchaControllerAPI::class, 'index']);
Route::get('/canchas/{id}', [CanchaControllerAPI::class, 'show']);
Route::get('/canchas/categoria/{id_categoria}', [CanchaControllerAPI::class, 'buscarPorCategoria']);

Route::get('turnos/disp', [TurnoControllerAPI::class, 'turnosDisponibles']);
Route::get('turnos/dispCat/{categoriaId}', [TurnoControllerAPI::class, 'turnosDisponiblesCategoria']);
Route::get('/turnos', [TurnoControllerAPI::class, 'index']);
Route::get('/turnos/{id}', [TurnoControllerAPI::class, 'show']);
Route::get('/turnos/cancha/{id_cancha}', [TurnoControllerAPI::class, 'buscarPorCancha']);
Route::get('/turnos/searchByDate/{fecha}', [TurnoControllerAPI::class, 'searchByDate']);
Route::get('/turnos/fecha/cat/{fecha}/{categoriaId}', [TurnoControllerAPI::class, 'searchByDateAndCategory']);

Route::post('/reservas/alta', [ReservaControllerAPI::class, 'altaReserva']);
Route::post('/reservas/misReservas', [ReservaControllerAPI::class, 'misReservas']);
Route::patch('/reservas/cancelar/{id_reserva}', [ReservaControllerAPI::class, 'cancelarReserva']);

Route::post('/mercadopago/notify', [MercadoPagoAPIController::class, 'notify']);

Route::get('/timezone-check', function () {
    return response()->json([
        'timezone' => date_default_timezone_get(),
        'datetime' => now(),
    ]);
});
