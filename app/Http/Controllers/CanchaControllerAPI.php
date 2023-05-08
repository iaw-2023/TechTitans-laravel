<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancha;
use App\Models\Categoria;

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
        $cancha = Cancha::where('nombre', $request->input('nombre'))
            ->where('id_categoria', $request->input('id_categoria'))
            ->first();

        if ($cancha) {
            return response()->json(['error' => 'Ya existe una cancha con el nombre indicado en esta categoría'], 404);
        }
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

    public function buscarPorCategoria(string $id_categoria)
    {
        $canchas = Cancha::where('id_categoria', $id_categoria)->get();
        if (!$canchas) {
            return response()->json(['error' => 'No se encontraron canchas para la categoría indicada'], 404);
        }
        return response()->json($canchas);
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
        return response()->json(['message' => 'Cancha eliminada correctamente']);
    }
}
