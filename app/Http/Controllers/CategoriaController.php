<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{

    public function __construct()
    {
        // Usar el formato correcto para aplicar múltiples middleware en un solo array
        $this->middleware([
            'role:admin',
            'permission:crear categorias',
        ])->only(['create', 'store']); 
     
        $this->middleware([
            'role:admin',
            'permission:editar categorias',
        ])->only(['edit', 'update']);
     
        $this->middleware([
            'role:admin',
            'permission:eliminar categorias',
        ])->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('Categoria.index')->with('categorias', $categorias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categorias = new Categoria();
        $categorias->nombre = $request->get('nombre');
        $categorias->save();
        return redirect('/categorias');
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
    public function edit($id)
    {
        $categoria = Categoria::find($id);
        return view('Categoria.edit')->with('categoria', $categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);
        $categoria->nombre = $request->get('nombre');
        $categoria->save();
        return redirect('/categorias');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        if($this->tieneCanchas($categoria)){
            session()->flash('error', 'No se puede eliminar esta categoria porque posee canchas vinculadas.');
            return redirect()->back();
        }else{
            $categoria->forceDelete();
        }
        session()->flash('success', 'La categoria se elimino correctamente.');
        return redirect('/categorias');
    }

    private function tieneCanchas($categoria) {
        $canchasAsociadas = $categoria->getCancha()->get();
        //dd($canchasAsociadas);
        foreach($canchasAsociadas as $cancha){
            $primero = $cancha->categoria()->first();
            if($primero)
                return true;
        }
        return false;
    }
}

