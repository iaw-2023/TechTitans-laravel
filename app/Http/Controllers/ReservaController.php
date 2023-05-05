<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Reserva;
use App\Models\ReservaDetalle;
use Illuminate\Http\Request;


class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        $detalle_reservas = DetalleReserva::all();
        return view('reserva.index')->with('reservas', $reservas)->with('detalle_reservas', $detalle_reservas);
    }
}