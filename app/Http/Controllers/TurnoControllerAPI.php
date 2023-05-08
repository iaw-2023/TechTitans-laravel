<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;

class TurnoControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = Turno::all();
        return response()->json($turnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $turno = new Turno;
        $turno->fecha_turno = $request->input('fecha_turno');
        $turno->hora_turno = $request->input('hora_turno');
        $turno->id_cancha = $request->input('id_cancha');
        $turno->save();
        return response()->json($turno);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['error' => 'Turno no encontrado'], 404);
        }
        return response()->json($turno);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['error' => 'Turno no encontrado'], 404);
        }
        $turno->fecha_turno = $request->input('fecha_turno');
        $turno->hora_turno = $request->input('hora_turno');
        $turno->id_cancha = $request->input('id_cancha');
        $turno->save();
        return response()->json($turno);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['error' => 'Turno no encontrado'], 404);
        }
        $turno->delete();
        return response()->json(['mensaje' => 'Turno eliminado correctamente']);
    }

    /**
     * Busca todos los turnos de una cancha específica.
     *
     * @param  int  $idCancha
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarPorCancha(int $idCancha)
    {
        $turnos = Turno::where('id_cancha', $idCancha)->get();
        return response()->json($turnos);
    }

    public function searchByDate(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);
        $turnos = Turno::where('fecha_turno', $request->input('fecha'))->get();
        return response()->json($turnos);
    }

    public function searchByDateAndCategory(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_categoria' => 'required|integer|exists:categorias,id',
        ]);

        $turnos = Turno::where('fecha_turno', $request->input('fecha'))
            ->whereHas('cancha', function ($query) use ($request) {
                $query->where('id_categoria', $request->input('id_categoria'));
            })
            ->get();

        return response()->json($turnos);
    }


}
