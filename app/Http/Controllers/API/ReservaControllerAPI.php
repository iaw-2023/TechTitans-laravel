<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetalleReserva;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Reserva;
use Carbon\Carbon;
use DateTime;

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
    public function altaReserva(Request $request){
        $mailCliente = $request->input('email_cliente');
        $cliente = Cliente::where('mail', $mailCliente)->first();
        if (!$cliente) {
            $nombre_usuario = strstr($mailCliente, '@', true);
            $cliente = Cliente::create([
                'nombre_usuario' => $nombre_usuario,
                'mail' => $mailCliente,
            ]);
        }
        $reserva = new Reserva();
        $reserva->fecha_reserva = now()->toDateString();
        $reserva->hora_reserva = now()->toTimeString();
        $reserva->email_cliente = $mailCliente;
        foreach ($request->input('turnos') as $turnoData) {
            $detalleReserva = new DetalleReserva();
            $detalleReserva->id_reserva = $reserva->id;
            $detalleReserva->id_turno = $turnoData['id_turno'];
            $detalleReserva->precio = $request->input('precio_total');
            $detalleReserva->save();
        }
        $reserva->save();
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