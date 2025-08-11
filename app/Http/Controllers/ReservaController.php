<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use App\Models\DetalleReserva;
use App\Models\Reserva;
use App\Models\Turno;
use App\Models\Cancha;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        return view('Reserva.index')->with('reservas', $reservas);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reserva = Reserva::find($id);
        $detalles = DetalleReserva::where('id_reserva', $id)->get();
        $turnos = [];
        foreach ($detalles as $detalle) {
            $turno = Turno::find($detalle->id_turno);
            if ($turno) {
                $turnos[] = $turno;
            }
        }
        return view('Reserva.show')->with('reserva', $reserva)->with('detalles', $detalles)->with('turnos', $turnos);
    }

    public function cancelarPendientes()
    {
        $limite = now()->subMinutes(10);

        $reservas = Reserva::where('estado', 'Pendiente')
            ->where('created_at', '<', $limite)
            ->get();

        foreach ($reservas as $reserva) {
            $reserva->estado = 'Cancelado';
            $reserva->updated_at = now();
            $reserva->save();

            foreach ($reserva->detalle_reserva as $detalle) {
                $detalle->cancelado = true;
                $detalle->updated_at = now();
                $detalle->save();
            }

            Log::info("Reserva ID {$reserva->id} cancelada. Tiempo de espera agotado. (EasyCron) ");
             
            $emailCliente = $reserva->email_cliente; 

            if ($emailCliente) {

                $this->enviarEmailCancelacion($emailCliente, $reserva->id, $reserva->detalle_reserva);
            } else {
                Log::warning("No se pudo enviar email de cancelación. Reserva ID {$reserva->id} sin email_cliente.");
            }
        }

        return response()->json([
            'status' => 'ok',
            'canceladas' => $reservas->count()
        ]);
    }

    private function enviarEmailCancelacion($emailCliente, $reservaId, $detallesReserva = null) {
        try {
            $emailController = new EmailController();

            if (!$detallesReserva) {
                $detallesReserva = DetalleReserva::where('id_reserva', $reservaId)->get();
            }

            $detalle = [];
            $precioTotal = 0;

            foreach ($detallesReserva as $detalleReserva) {
                $turno = Turno::find($detalleReserva->id_turno);
                if ($turno) {
                    $cancha = Cancha::find($turno->id_cancha);
                    if ($cancha) {
                        $categoria = Categoria::find($cancha->id_categoria);
                        $detalle[] = [
                            'categoria'      => $categoria ? $categoria->nombre : 'N/A',
                            'fecha'          => $turno->fecha_turno,
                            'hora'           => $turno->hora_turno,
                            'nombre_cancha'  => $cancha->nombre,
                            'precio'         => $cancha->precio,
                            'techo'          => $cancha->techo,
                            'cant_jugadores' => $cancha->cant_jugadores,
                            'superficie'     => $cancha->superficie,
                            'precio_total'   => $detalleReserva->precio
                        ];
                        $precioTotal += $detalleReserva->precio;
                    }
                }
            }

            $requestData = [
                'email'          => $emailCliente,
                'detalleReserva' => $detalle,
                'precio_total'   => $precioTotal,
                'esCancelacion'  => true
            ];

            $request = new Request($requestData); 
            $emailController->sendEmail($request);

            Log::info('Email de cancelación enviado a: ' . $emailCliente . ' (reserva ' . $reservaId . ')');
        } catch (\Exception $e) {
            Log::error('Error al enviar email de cancelación (reserva ' . $reservaId . '): ' . $e->getMessage());
        }
    }
}