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
 
         $detallesConTurnos = $detalles->map(function ($detalle) {
             $turno = Turno::find($detalle->id_turno);
             if ($turno) {
                 $cancha = Cancha::with('categoria')->find($turno->id_cancha); // Carga la relación de categoría
                 return [
                     'turno' => $turno,
                     'cancha' => $cancha,
                 ];
             }
             return null; // Excluye turnos inválidos
         })->filter(); // Elimina los turnos nulos
 
         $reservasConDetalles[] = [
             'reserva' => $reserva,
             'detalle' => $detalles,
             'turnos' => $detallesConTurnos,
         ];
     }
 
     return response()->json($reservasConDetalles, 200);
 }
 

/**
 * @OA\Patch(
 *     path="/rest/reservas/cancelar/{id_reserva}",
 *     summary="Cancelar una reserva",
 *     description="Marca una reserva como cancelada y actualiza sus detalles si aplica.",
 *     tags={"Reservas"},
 *     @OA\Parameter(
 *         name="id_reserva",
 *         in="path",
 *         description="ID de la reserva a cancelar",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reserva cancelada exitosamente.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reserva cancelada con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reserva no encontrada"
 *     )
 * )
 */
public function cancelarReserva($id_reserva)
{
    try {
        // Verificar si el ID de la reserva es válido
        if (!$id_reserva) {
            return response()->json([
                'debug' => 'El ID de la reserva no fue proporcionado.',
            ], 400);
        }

        // Buscar la reserva
        $reserva = Reserva::find($id_reserva);

        if (!$reserva) {
            return response()->json([
                'debug' => 'No se encontró la reserva',
                'id_reserva' => $id_reserva,
            ], 404);
        }

        // Verificar si ya está cancelada
        if ($reserva->estado === 'Cancelado') {
            return response()->json([
                'debug' => 'La reserva ya está cancelada',
                'reserva' => $reserva,
            ], 200);
        }

        // Actualizar el estado
        $reserva->estado = 'Cancelado';
        $reserva->save();

        // Obtener los detalles de la reserva
        $detalles = DetalleReserva::where('id_reserva', $id_reserva)->get();

        foreach ($detalles as $detalle) {
            // Marcar el detalle como cancelado
            $detalle->cancelado = true;
            $detalle->updated_at = now();
            $detalle->save();
        }
        
        return response()->json([
            'debug' => 'Reserva cancelada y turnos liberados con éxito',
            'reserva' => $reserva,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'debug' => 'Excepción encontrada',
            'error_message' => $e->getMessage(),
            'stack_trace' => $e->getTrace(),
        ], 500);
    }
}

}