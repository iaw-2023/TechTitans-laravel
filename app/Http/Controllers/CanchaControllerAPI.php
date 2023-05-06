<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancha;

class CanchaControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canchas = Cancha::all();
        return response()->json($canchas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cancha = new Cancha();
        $cancha->nombre = $request->input('nombre');
        $cancha->precio = $request->input('precio');
        $cancha->techo = $request->input('techo');
        $cancha->cant_jugadores = $request->input('cant_jugadores');
        $cancha->superficie = $request->input('superficie');
        $cancha->id_categoria = $request->input('id_categoria');
        $cancha->activo = $request->input('activo', true);
        $cancha->save();
        return response()->json($cancha);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cancha = Cancha::find($id);
        if (!$cancha) {
            return response()->json(['error' => 'Cancha no encontrada'], 404);
        }
        return response()->json($cancha);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cancha = Cancha::find($id);
        if (!$cancha) {
            return response()->json(['error' => 'Cancha no encontrada'], 404);
        }
        $cancha->nombre = $request->input('nombre');
        $cancha->precio = $request->input('precio');
        $cancha->techo = $request->input('techo');
        $cancha->cant_jugadores = $request->input('cant_jugadores');
        $cancha->superficie = $request->input('superficie');
        $cancha->id_categoria = $request->input('id_categoria');
        $cancha->activo = $request->input('activo', true);
        $cancha->save();
        return response()->json($cancha);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cancha = Cancha::find($id);
        if (!$cancha) {
            return response()->json(['error' => 'Cancha no encontrada'], 404);
        }
        $cancha->delete();
        return response()->json('Cancha eliminada correctamente');
    }
}
