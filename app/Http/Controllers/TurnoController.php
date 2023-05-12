<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Cancha;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = Turno::all();
        return view('Turno.index')->with('turnos', $turnos);
    }    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $canchas = Cancha::all();
        return view('Turno.create', ['categorias' => $categorias, 'canchas' => $canchas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_cancha' => 'required',
            'hora_turno' => 'required',
            'fecha_turno' => 'required|date',
        ]);
        $turno = new Turno();
        $turno->id_cancha = $request->get('id_cancha');
        $turno->hora_turno = $request->get('hora_turno');
        $turno->fecha_turno = $request->get('fecha_turno');
        $turno->save();
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
        return view('Turno.edit')->with('turno', $turno);
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
