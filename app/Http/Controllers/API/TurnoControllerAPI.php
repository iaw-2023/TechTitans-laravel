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

    /**
     * @OA\Get(
     *     path="/rest/turnos/dispCat/{categoriaId}",
     *     summary="Obtener los turnos disponibles por categoría",
     *     description="Retorna una lista de los turnos disponibles para una categoría específica.",
     *     tags={"Turnos"},
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos disponibles obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="2023-05-01"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(
     *                     property="cancha",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Cancha 1"),
     *                     @OA\Property(property="precio", type="number", example=3000),
     *                     @OA\Property(property="techo", type="boolean", example=true),
     *                     @OA\Property(property="cant_jugadores", type="integer", example=4),
     *                     @OA\Property(property="superficie", type="string", example="Cemento"),
     *                     @OA\Property(property="id_categoria", type="integer", example=1),
     *                     @OA\Property(property="activo", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La categoría no existe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La categoría no existe")
     *         )
     *     )
     * )
     */
    public function turnosDisponiblesCategoria($categoriaId)
    {
        $categoria = Categoria::find($categoriaId);
        if (!$categoria) {
            return response()->json(['error' => 'La categoría no existe'], 404);
        }
        $canchas = Cancha::where('id_categoria', $categoria->id)->get();
        $canchasIds = $canchas->pluck('id')->toArray();

        $turnosSinReservas = Turno::whereIn('id_cancha', $canchasIds)
            ->whereNotIn('id', function ($query) {
                $query->select('id_turno')
                    ->from('detalle_reservas');
            })
            ->with('cancha')
            ->get();
        return response()->json($turnosSinReservas);
    }

    /**
     * @OA\Get(
     *     path="/rest/turnos/dispCat",
     *     summary="Obtener todos los turnos disponibles",
     *     description="Retorna una lista de todos los turnos disponibles.",
     *     tags={"Turnos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos disponibles obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="2023-05-01"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(
     *                     property="cancha",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Cancha 1"),
     *                     @OA\Property(property="precio", type="number", example=3000),
     *                     @OA\Property(property="techo", type="boolean", example=true),
     *                     @OA\Property(property="cant_jugadores", type="integer", example=4),
     *                     @OA\Property(property="superficie", type="string", example="Cemento"),
     *                     @OA\Property(property="id_categoria", type="integer", example=1),
     *                     @OA\Property(property="activo", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function turnosDisponibles()
    {
        $canchasIds = Cancha::pluck('id')->toArray();
        $turnosSinReservas = Turno::whereIn('id_cancha', $canchasIds)
            ->whereNotIn('id', function ($query) {
                $query->select('id_turno')
                    ->from('detalle_reservas');
            })
            ->with('cancha')
            ->get();
        return response()->json($turnosSinReservas);
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
     *     path="/rest/turnos/fecha/{fecha}",
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
     * @OA\Get(
     *     path="/rest/turnos/fecha/cat/{fecha}/{categoriaId}",
     *     summary="Buscar turnos disponibles por fecha y categoría",
     *     description="Retorna una lista de los turnos disponibles para una fecha y categoría específicas.",
     *     tags={"Turnos"},
     *     @OA\Parameter(
     *         name="fecha",
     *         in="path",
     *         description="Fecha del turno (formato: yyyy-mm-dd)",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2023-05-01"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="categoriaId",
     *         in="path",
     *         description="ID de la categoría",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos disponibles obtenida exitosamente.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="fecha_turno", type="string", format="date", example="2023-05-01"),
     *                 @OA\Property(property="hora_turno", type="string", format="time", example="18:00:00"),
     *                 @OA\Property(
     *                     property="cancha",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Cancha 1"),
     *                     @OA\Property(property="precio", type="number", example=3000),
     *                     @OA\Property(property="techo", type="boolean", example=true),
     *                     @OA\Property(property="cant_jugadores", type="integer", example=4),
     *                     @OA\Property(property="superficie", type="string", example="Cemento"),
     *                     @OA\Property(property="id_categoria", type="integer", example=1),
     *                     @OA\Property(property="activo", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La categoría no existe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="La categoría no existe")
     *         )
     *     )
     * )
     */
    public function searchByDateAndCategory($fecha, $categoriaId)
    {
         $categoria = Categoria::find($categoriaId);
        if (!$categoria) {
            return response()->json(['error' => 'La categoría no existe'], 404);
        }

        $canchas = Cancha::where('id_categoria', $categoria->id)->get();
        $canchasIds = $canchas->pluck('id')->toArray();

        $turnosSinReservas = Turno::whereIn('id_cancha', $canchasIds)
            ->where('fecha_turno', $fecha) // Agregar la condición de fecha
            ->whereNotIn('id', function ($query) {
                $query->select('id_turno')
                    ->from('detalle_reservas');
            })
            ->with('cancha')
            ->get();

        return response()->json($turnosSinReservas);
    }
}