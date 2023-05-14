<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancha;
use App\Models\Categoria;
use App\Models\DetalleReserva;

class CanchaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canchas = Cancha::all();
        return view('Cancha.index')->with('canchas', $canchas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('Cancha.create')->with('categorias', $categorias);;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $canchas = new Cancha();
        $canchas->nombre = $request->get('nombre');
        $canchas->precio = $request->get('precio');
        $canchas->techo = $request->get('techo');
        $canchas->cant_jugadores = $request->get('cant_jugadores');
        $canchas->superficie = $request->get('superficie');
        $canchas->id_categoria = $request->get('id_categoria');
        $canchas->activo = $request->get('activo');
        $canchas->save();
        return redirect('/canchas');
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
        $categorias = Categoria::all();
        $cancha = Cancha::find($id);
        return view('Cancha.edit')->with('cancha', $cancha)->with('categorias', $categorias);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cancha = Cancha::find($id);
        $cancha->nombre = $request->get('nombre');
        $cancha->precio = $request->get('precio');
        $cancha->techo = $request->get('techo');
        $cancha->cant_jugadores = $request->get('cant_jugadores');
        $cancha->superficie = $request->get('superficie');
        $cancha->id_categoria = $request->get('id_categoria');
        $cancha->activo = $request->get('activo');
        $cancha->save();
        return redirect('/canchas');
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy(string $id)
    {
        
        $cancha = Cancha::findOrFail($id);
        $turnosAsociados = $cancha->getTurnos()->get();
        //dd($turnosAsociados); checkkkkkkkkk
        if($this->tienenReservasAsociadas($turnosAsociados)){
            session()->flash('error', 'No se puede eliminar esta cancha porque tiene turnos reservados.');
            return redirect()->back();
        } else{
            foreach ($turnosAsociados as $turno) {
                $turno->forceDelete();
            }
            $cancha->forceDelete();
            session()->flash('success', 'La cancha se elimino correctamente.');
            return redirect('/canchas');
            }
        }

        private function tienenReservasAsociadas($turnosAsociados){
            foreach ($turnosAsociados as $turno) {
                $detalleReserva = DetalleReserva::find($turno->id);
                if($detalleReserva){
                    return true;
                }
            }
            return false;
        }
}