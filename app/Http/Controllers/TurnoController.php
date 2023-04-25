<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = Turno::all();
        return view('turno.index')->with('turnos', $turnos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('turno.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $turnos = new Turno();
        $turnos->id_cancha = $request->get('id_cancha');
        $turnos->hora_turno = $request->get('hora_turno');
        $turnos->fecha_turno = $request->get('fecha_turno');
        $turnos->save();
        return redirect('/turnos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $turno = Turno::find($id);
        return view('turno.edit')->with('turno', $turno);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $turno = Turno::find($id);
        $turno->id_cancha = $request->get('id_cancha');
        $turno->hora_turno = $request->get('hora_turno');
        $turno->fecha_turno = $request->get('fecha_turno');
        $turno->save();
        return redirect('/turnos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $turno = Turno::find($id);
        $turno->delete();
        return redirect('/turnos');
    }
}
