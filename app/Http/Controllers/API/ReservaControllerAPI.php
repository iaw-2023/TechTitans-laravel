<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetalleReserva;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Reserva;
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
            DB::table('detalle_reservas')->insert([
                'precio' => $precioTotal,
                'id_reserva' => $reservaId,
                'id_turno' => $idTurno,
                'cancelado' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }        
        return response()->json(['message' => 'Reserva creada con éxito'], 201);
    }

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
            $turnos = [];
            foreach ($detalles as $detalle) {
                $turno = Turno::find($detalle->id_turno);
                if ($turno) {
                    $turnos[] = $turno;
                }
            }
            $reservasConDetalles[] = [
                'reserva' => $reserva,
                'detalles' => $detalles,
                'turnos' => $turnos
            ];
        }
        return response()->json($reservasConDetalles, 200);
    }


}