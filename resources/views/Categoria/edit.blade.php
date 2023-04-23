@extends('layouts.plantillabase');

@section('contenido')
<h2>EDITAR CATEGORIA</h2>

<form action="/categorias/{{$categoria->id}}" method="POST">
    @csrf    
    @method('PUT')
  <div class="mb-3">
    <label for="" class="form-label">Nombre</label>
    <input id="nombre" name="nombre" type="text" class="form-control" value="{{$categoria->nombre}}">    
  </div>
  <a href="/articulos" class="btn btn-secondary">Cancelar</a>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

@endsection