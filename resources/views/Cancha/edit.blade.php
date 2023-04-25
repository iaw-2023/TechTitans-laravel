@extends('layouts.plantillabase');

@section('contenido')
<h2>EDITAR CANCHA</h2>

<form action="/canchas/{{$cancha->id}}" method="POST">
    @csrf    
    @method('PUT')
  <div class="mb-3">
    <label for="" class="form-label">Nombre</label>
    <input id="nombre" name="nombre" type="text" class="form-control" value="{{$cancha->nombre}}">    
  </div>
  <div class="mb-3">
    <label for="" class="form-label">Precio</label>
    <input id="precio" name="precio" type="text" class="form-control" value="{{$cancha->precio}}">    
  </div>
  <div class="mb-3">
    <label for="techo" class="form-label">Techo</label>
    <select id="techo" name="techo" class="form-select">
        <option value="1" {{ $cancha->techo ? 'selected' : '' }}>Si</option>
        <option value="0" {{ $cancha->techo ? '' : 'selected' }}>No</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="" class="form-label">Cant. Jugadores</label>
    <input id="cant_jugadores" name="cant_jugadores" type="text" class="form-control" value="{{$cancha->cant_jugadores}}">    
  </div>
  <div class="mb-3">
    <label for="" class="form-label">Superficie</label>
    <input id="superficie" name="superficie" type="text" class="form-control" value="{{$cancha->superficie}}">    
  </div>
  <div class="mb-3">
    <label for="" class="form-label">ID Categoria</label>
    <input id="id_categoria" name="id_categoria" type="text" class="form-control" value="{{$cancha->id_categoria}}">    
  </div>
  <div class="mb-3">
    <label for="activo" class="form-label">Activo</label>
    <select id="activo" name="activo" class="form-select">
        <option value="1" {{ $cancha->activo ? 'selected' : '' }}>Si</option>
        <option value="0" {{ $cancha->activo ? '' : 'selected' }}>No</option>
    </select>
</div>

  <a href="/canchas" class="btn btn-secondary">Cancelar</a>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

@endsection