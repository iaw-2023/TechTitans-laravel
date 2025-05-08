<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Reserva;
use App\Models\Turno;
use Illuminate\Support\Facades\Log;


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
        $limite = now()->subMinutes(5);

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
        }

        return response()->json([
            'status' => 'ok',
            'canceladas' => $reservas->count()
        ]);
    }
}