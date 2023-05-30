<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categoria;

class CategoriaControllerAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/rest/categorias",
     *     summary="Obtener lista de categorías",
     *     description="Retorna una lista de todas las categorías existentes en el sistema.",
     *     tags={"Categorías"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="nombre", type="string", maxLength=50, nullable=true, example="Futbol")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * @OA\Get(
     *     path="/rest/categorias/{id}",
     *     summary="Obtener detalles de una categoría",
     *     description="Retorna los detalles de una categoría específica.",
     *     tags={"Categorías"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría a obtener.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la categoría obtenidos exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="nombre", type="string", maxLength=50, nullable=true, example="Nombre de la categoría"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-05-18 12:34:56"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-05-18 12:34:56")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }
        return response()->json($categoria);
    }
}