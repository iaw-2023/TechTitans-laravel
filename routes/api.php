<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaControllerAPI;
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
Route::post('/categorias', [CategoriaControllerAPI:: class, 'store']);
Route::get('/categorias/{id}', [CategoriaControllerAPI:: class, 'show']);
Route::put('/categorias/{id}', [CategoriaControllerAPI:: class, 'update']);
Route::delete('/categorias/{id}', [CategoriaControllerAPI:: class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
