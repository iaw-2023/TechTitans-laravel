<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use App\Models\Cancha;
use App\Models\Categoria;

class TurnoControllerAPI extends Controller
{
    /**
     * @OA\Get(
     *     path="/rest/turnos",
     *     summary="Obtener todos los turnos",
     *     description="Retorna una lista de todos los turnos.",
     *     tags={"Turnos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="18/05/2023"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(property="id_cancha", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $turnos = Turno::all();
        return response()->json($turnos);
    }

    public function obtenerTurnosPorCategoria($nombreCategoria)
    {
        // Buscar la categoría por nombre
        $categoria = Categoria::where('nombre', $nombreCategoria)->first();

        if (!$categoria) {
            // Si la categoría no existe, puedes retornar un mensaje de error o un código de respuesta adecuado.
            return response()->json(['error' => 'La categoría no fue encontrada'], 404);
        }

        // Obtener todas las canchas asociadas a la categoría
        $canchas = Cancha::where('id_categoria', $categoria->id)->get();

        // Obtener los IDs de las canchas
        $canchasIds = $canchas->pluck('id')->toArray();

        // Obtener todos los turnos asociados a las canchas de la categoría
        $turnos = Turno::whereIn('id_cancha', $canchasIds)->get();

        // Retornar los turnos en formato JSON
        return response()->json($turnos);
    }


    /**
     * @OA\Get(
     *     path="/rest/turnos/{id}",
     *     summary="Obtener un turno por su ID",
     *     description="Retorna los detalles de un turno específico.",
     *     tags={"Turnos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del turno a obtener.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del turno obtenidos exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="fecha_turno", type="string", format="date", example="18/05/2023"),
     *             @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *             @OA\Property(property="id_cancha", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Turno no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['error' => 'Turno no encontrado'], 404);
        }
        return response()->json($turno);
    }

    /**
     * @OA\Get(
     *     path="/rest/turnos/cancha/{id_cancha}",
     *     summary="Buscar turnos por cancha",
     *     description="Retorna los turnos disponibles para una cancha específica.",
     *     tags={"Turnos"},
     *     @OA\Parameter(
     *         name="idCancha",
     *         in="path",
     *         description="ID de la cancha para buscar los turnos.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos para la cancha obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="18/05/2023"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(property="id_cancha", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function buscarPorCancha(int $idCancha)
    {
        $turnos = Turno::where('id_cancha', $idCancha)->get();
        return response()->json($turnos);
    }

    /**
     * @OA\Get(
     *     path="/rest/turnos/fecha/{fecha_turno}",
     *     summary="Buscar turnos por fecha",
     *     description="Retorna los turnos disponibles para una fecha específica.",
     *     tags={"Turnos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha", type="string", format="date", example="18/05/2023")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos para la fecha especificada obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="18/05/2023"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(property="id_cancha", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación. La fecha proporcionada no es válida."
     *     )
     * )
     */
    public function searchByDate($fecha)
    {
        $turnos = Turno::where('fecha_turno', $fecha)->get();
        return response()->json($turnos);
    }

    /**
     * @OA\Post(
     *     path="/rest/turnos/fecha/categoria/{fecha_turno}/{id_categoria}",
     *     summary="Buscar turnos por fecha y categoría",
     *     description="Retorna los turnos disponibles para una fecha específica y una categoría específica de cancha.",
     *     tags={"Turnos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha", type="string", format="date", example="18/05/2023"),
     *             @OA\Property(property="id_categoria", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos para la fecha y categoría especificadas obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="18/05/2023"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(property="id_cancha", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación. La fecha o la categoría proporcionada no son válidas."
     *     )
     * )
     */
    public function searchByDateAndCategory($fecha, $id_categoria)
    {
        $turnos = Turno::where('fecha_turno', $fecha)
            ->whereHas('cancha', function ($query) use ($id_categoria) {
                $query->where('id_categoria', $id_categoria);
            })
            ->get();

        return response()->json($turnos);
    }
}