<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Reserva;


class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $detalle_reservas = DetalleReserva::all();
        return view('Reserva.index')->with('reservas', $reservas)->with('detalle_reservas', $detalle_reservas);
    }
}