<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetalleReserva;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Reserva;
use Carbon\Carbon;

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
        $request->validate([
            'email_cliente' => 'required|email',
            'turnos' => 'required|array',
            'turnos.*.id_turno' => 'required|exists:turnos,id',
            'turnos.*.precio' => 'required|numeric',
            'precio_total' => 'required|numeric',
        ]);
        $mailCliente = $request->input('email_cliente');
        $cliente = Cliente::where('mail', $mailCliente);
        if (!$cliente) {
            $nombre_usuario = strstr($mailCliente, '@', true);
            $cliente = new Cliente();
            $cliente->nombre_usuario = $nombre_usuario;
            $cliente->email = $mailCliente;
            $cliente->save();       
        }
        $reserva = new Reserva();
        $reserva->fecha_reserva = Carbon::now()->format('Y-m-d');
        $reserva->hora_reserva = Carbon::now()->format('H:i:s');
        $reserva->email_cliente = $mailCliente;
        $reserva->save();
        foreach ($request->turnos as $turnoData) {
            $detalleReserva = new DetalleReserva();
            $detalleReserva->id_reserva = $reserva->id;
            $detalleReserva->id_turno = $turnoData['id_turno'];
            $detalleReserva->precio = $request->input('precio_total');
        }
        return response()->json(['message' => 'Reserva creada con éxito'], 201);
    }
}