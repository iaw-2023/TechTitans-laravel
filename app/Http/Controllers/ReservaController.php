<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Reserva;
use App\Models\Turno;


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
}