<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cancha;

class CanchaControllerAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/rest/canchas",
     *     summary="Obtener lista de canchas",
     *     description="Retorna una lista de todas las canchas existentes en el sistema.",
     *     tags={"Canchas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de canchas obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="nombre", type="string", maxLength=50, nullable=true, example="Nombre de la cancha"),
     *                 @OA\Property(property="precio", type="number", format="float", nullable=true, example="10.5"),
     *                 @OA\Property(property="techo", type="boolean", example=true),
     *                 @OA\Property(property="cant_jugadores", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="superficie", type="string", nullable=true, example="Césped"),
     *                 @OA\Property(property="id_categoria", type="integer", example=1),
     *                 @OA\Property(property="activo", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $canchas = Cancha::all();
        return response()->json($canchas);
    }

    /**
     * @OA\Get(
     *     path="/rest/canchas/{id}",
     *     summary="Obtener detalles de una cancha",
     *     description="Retorna los detalles de una cancha específica.",
     *     tags={"Canchas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cancha a obtener.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la cancha obtenidos exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="nombre", type="string", maxLength=50, nullable=true, example="Nombre de la cancha"),
     *             @OA\Property(property="precio", type="number", format="float", nullable=true, example="10.5"),
     *             @OA\Property(property="techo", type="boolean", example=true),
     *             @OA\Property(property="cant_jugadores", type="integer", nullable=true, example=5),
     *             @OA\Property(property="superficie", type="string", nullable=true, example="Césped"),
     *             @OA\Property(property="id_categoria", type="integer", example=1),
     *             @OA\Property(property="activo", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cancha no encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $cancha = Cancha::find($id);
        if (!$cancha) {
            return response()->json(['error' => 'Cancha no encontrada'], 404);
        }
        return response()->json($cancha);  
    }

    /**
     * @OA\Get(
     *     path="/rest/canchas/categoria/{id_categoria}",
     *     summary="Buscar canchas por categoría",
     *     description="Retorna una lista de canchas que pertenecen a una categoría específica.",
     *     tags={"Canchas"},
     *     @OA\Parameter(
     *         name="id_categoria",
     *         in="path",
     *         description="ID de la categoría para buscar las canchas.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de canchas obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="nombre", type="string", maxLength=50, nullable=true, example="Nombre de la cancha"),
     *                 @OA\Property(property="precio", type="number", format="float", nullable=true, example="10.5"),
     *                 @OA\Property(property="techo", type="boolean", example=true),
     *                 @OA\Property(property="cant_jugadores", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="superficie", type="string", nullable=true, example="Césped"),
     *                 @OA\Property(property="id_categoria", type="integer", example=1),
     *                 @OA\Property(property="activo", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron canchas para la categoría indicada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function buscarPorCategoria(string $id_categoria)
    {
        $canchas = Cancha::where('id_categoria', $id_categoria)->get();
        if (!$canchas) {
            return response()->json(['error' => 'No se encontraron canchas para la categoría indicada'], 404);
        }
        return response()->json($canchas);
    }
}
