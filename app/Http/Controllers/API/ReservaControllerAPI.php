<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use App\Models\DetalleReserva;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Cancha;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;

class ReservaControllerAPI extends Controller
{
    /**
     * @OA\Post(
     *     path="/rest/reservas/alta",
     *     summary="Crear una nueva reserva",
     *     description="Crea una nueva reserva para un cliente, registrando los detalles de los turnos reservados.",
     *     tags={"Reservas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email_cliente", type="string", format="email", example="raul@gmail.com"),
     *             @OA\Property(property="turnos", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_turno", type="integer", example=1),
     *                     @OA\Property(property="precio", type="number", example=600)
     *                 )
     *             ),
     *             @OA\Property(property="precio_total", type="number", example=2600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reserva creada con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación. El correo electrónico del cliente, los turnos o el precio total no son válidos."
     *     )
     * )
     */
    function altaReserva(Request $request)
    {
        $jsonData = $request->json()->all();
        $emailCliente = $jsonData['email_cliente'];
        $turnos = $jsonData['turnos'];
        $precioTotal = $jsonData['precio_total'];
        $cliente = DB::table('cliente')->where('mail', $emailCliente)->first();
        if (!$cliente) {
            DB::table('cliente')->insert([
                'mail' => $emailCliente,
                'nombre_usuario' => strstr($emailCliente, '@', true),
            ]);
        }
        $reservaId = DB::table('reservas')->insertGetId([
            'fecha_reserva' => now()->format('Y-m-d'),
            'hora_reserva' => now()->format('H:i:s'),
            'email_cliente' => $emailCliente,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ($turnos as $turno) {
            $idTurno = $turno['id_turno'];
            $turnoExistente = DB::table('turnos')->where('id', $idTurno)->first();
            DB::table('detalle_reservas')->insert([
                'precio' => $precioTotal,
                'id_reserva' => $reservaId,
                'id_turno' => $idTurno,
                'cancelado' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }    
        $this->enviarEmail($emailCliente, $reservaId);
        return response()->json(['message' => 'Reserva creada con éxito'], 201);
    }

    private function enviarEmail($emailCliente, $reservaId) {
        $emailController = new EmailController();
        $detallesReserva = DetalleReserva::select('id_turno', 'id_reserva', 'precio')->where('id_reserva', $reservaId)->get();
        $detalle = [];
        foreach ($detallesReserva as $detalleReserva) {
            $turno = Turno::find($detalleReserva->id_turno);
            $cancha = Cancha::find($turno->id_cancha);
            $categoria = Categoria::find($cancha->id_categoria);
            $detalle[] = [
                'categoria' => $categoria->nombre,
                'fecha' => $turno->fecha_turno,
                'hora' => $turno->hora_turno,
                'nombre_cancha' => $cancha->nombre,
                'precio' => $cancha->precio,
                'techo' => $cancha->techo,
                'cant_jugadores' => $cancha->cant_jugadores,
                'superficie' => $cancha->superficie,
                'precio_total' => $detallesReserva->precio
            ];
        }
        $requestData = [
            'email' => $emailCliente,
            'detalleReserva' => $detalle
        ];
        $request = App::make('request');
        $request->merge($requestData);
        $emailController->sendEmail($request);
    }

    /**
     * @OA\Get(
     *     path="/rest/reservas/misReservas/{mailCliente}",
     *     summary="Obtener las reservas de un cliente",
     *     description="Obtiene las reservas realizadas por un cliente junto con los detalles de los turnos y las canchas correspondientes.",
     *     tags={"Reservas"},
     *     @OA\Parameter(
     *         name="mailCliente",
     *         in="query",
     *         description="Correo electrónico del cliente",
     *         required=true,
     *         @OA\Schema(type="string", format="email", example="raul@gmail.com")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="reserva",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="fecha_reserva", type="string", format="date", example="2023-05-18"),
     *                     @OA\Property(property="hora_reserva", type="string", format="time", example="10:00:00"),
     *                     @OA\Property(property="email_cliente", type="string", format="email", example="raul@gmail.com")
     *                 ),
     *                 @OA\Property(
     *                     property="detalle",
     *                     type="object",
     *                     @OA\Property(property="precio", type="number", example=600),
     *                     @OA\Property(property="id_reserva", type="integer", example=1),
     *                     @OA\Property(property="id_turno", type="integer", example=1),
     *                     @OA\Property(property="cancelado", type="boolean", example=false)
     *                 ),
     *                 @OA\Property(
     *                     property="turnos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="turno",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="fecha_turno", type="string", format="date", example="2023-05-18"),
     *                             @OA\Property(property="hora_turno", type="string", format="time", example="10:00:00"),
     *                             @OA\Property(property="id_cancha", type="integer", example=1)
     *                         ),
     *                         @OA\Property(
     *                             property="cancha",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="nombre", type="string", example="Cancha A"),
     *                             @OA\Property(property="precio", type="number", example=1000),
     *                             @OA\Property(property="techo", type="boolean", example=true),
     *                             @OA\Property(property="cant_jugadores", type="integer", example=6),
     *                             @OA\Property(property="superficie", type="string", example="Césped"),
     *                             @OA\Property(property="id_categoria", type="integer", example=1),
     *                             @OA\Property(property="activo", type="boolean", example=true)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado o sin reservas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El cliente no existe o no tiene reservas")
     *         )
     *     )
     * )
     */
    public function misReservas($mailCliente)
    {
        $cliente = Cliente::where('mail', $mailCliente)->first();
        if (!$cliente) {
            return response()->json(['message' => 'El cliente no existe'], 404);
        }

        $reservas = Reserva::where('email_cliente', $mailCliente)->get();
        if ($reservas->isEmpty()) {
            return response()->json(['message' => 'El cliente no tiene reservas'], 404);
        }

        $reservasConDetalles = [];
        foreach ($reservas as $reserva) {
            $detalles = DetalleReserva::where('id_reserva', $reserva->id)->get();
            $detallesConTurnos = [];
            foreach ($detalles as $detalle) {
                $turno = Turno::find($detalle->id_turno);
                if ($turno) {
                    $cancha = Cancha::find($turno->id_cancha);
                    $detallesConTurnos[] = [$turno, $cancha];
                }
            }
            $reservasConDetalles[] = [$reserva, $detalle, $detallesConTurnos];
        }
        return response()->json($reservasConDetalles, 200);
    }
}