<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetalleReserva;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Cancha;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $precio = $turno['precio'];
            $turnoExistente = DB::table('turnos')->where('id', $idTurno)->first();
            DB::table('detalle_reservas')->insert([
                'precio' => $precio,
                'id_reserva' => $reservaId,
                'id_turno' => $idTurno,
                'cancelado' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Procedimiento de MercadoPago
        $mercadoPagoController = new MercadoPagoAPIController();
        $preferenceResponse = $mercadoPagoController->createPreference($request, $reservaId);

        if ($preferenceResponse->getStatusCode() != 200) {
            return response()->json(['message' => 'Error al crear la preferencia de MercadoPago'], 500);
        }
        $preferenceData = $preferenceResponse->getData();
        Log::info('Respuesta de Mercado Pago:', (array) $preferenceResponse->getData());

        // Envío de email    
        $this->enviarEmail($emailCliente, $reservaId);
        
        return response()->json([
            'message' => 'Reserva creada con éxito',
            'preference_id' => $preferenceData->preference_id,
        ], 201);
    }

    private function enviarEmail($emailCliente, $reservaId) {
        $emailController = new EmailController();
        $detallesReserva = DetalleReserva::select('id_turno', 'id_reserva', 'precio')->where('id_reserva', $reservaId)->get();
        $detalle = [];
        $precioTotal = 0;
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
                'precio_total' => $detalleReserva->precio
            ];
            $precioTotal = $detalleReserva->precio;
        }
        $requestData = [
            'email' => $emailCliente,
            'detalleReserva' => $detalle,
            'precio_total' => $precioTotal
        ];
        $request = Request::create('', 'POST', $requestData);
        $emailController->sendEmail($request);
    }
    
    /**
 * @OA\Post(
 *     path="/rest/reservas/misReservas",
 *     summary="Obtener las reservas de un cliente",
 *     description="Obtiene las reservas realizadas por un cliente junto con los detalles de los turnos y las canchas correspondientes.",
 *     tags={"Reservas"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email_cliente", type="string", format="email", example="raul@gmail.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="reserva", type="object"),
 *                 @OA\Property(property="detalle", type="array"),
 *                 @OA\Property(property="turnos", type="array")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación. El correo electrónico es obligatorio."
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="El cliente no existe o no tiene reservas.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="El cliente no tiene reservas")
 *         )
 *     )
 * )
 */

    public function misReservas(Request $request)
{
    $emailCliente = $request->input('email_cliente');

    if (!$emailCliente) {
        return response()->json(['message' => 'El correo electrónico es obligatorio'], 400);
    }

    $cliente = Cliente::where('mail', $emailCliente)->first();

    if (!$cliente) {
        return response()->json(['message' => 'El cliente no existe'], 404);
    }

    $reservas = Reserva::where('email_cliente', $emailCliente)->get();

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
                $detallesConTurnos[] = [
                    'turno' => $turno,
                    'cancha' => $cancha,
                ];
            }
        }
        $reservasConDetalles[] = [
            'reserva' => $reserva,
            'detalle' => $detalles,
            'turnos' => $detallesConTurnos,
        ];
    }

    return response()->json($reservasConDetalles, 200);
}

}