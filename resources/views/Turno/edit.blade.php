@extends('layouts.plantillabase');

@section('contenido')
<h2 style= "color:#ffffff;">EDITAR TURNO</h2>

<form action="/turnos/{{$turno->id}}" method="POST">
    @csrf    
    @method('PUT')
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Cancha</label>
    <input id="id_cancha" name="id_cancha" type="text" class="form-control" value="{{$turno->id_cancha}}">    
  </div>
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Hora</label>
    <input id="hora_turno" name="hora_turno" type="text" class="form-control" value="{{$turno->hora_turno}}">    
  </div>
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Fecha</label>
    <input id="fecha_turno" name="fecha_turno" type="text" class="form-control" value="{{$turno->fecha_turno}}">    
  </div>
  <a href="/turnos" class="btn btn-secondary">Cancelar</a>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

@endsection