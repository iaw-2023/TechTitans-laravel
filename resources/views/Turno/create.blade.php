@extends('layouts.plantillabase');

@section('contenido')
<h2 style="color: #ffffff;" > CREAR TURNO</h2>

<form action="/turnos" method="POST">  
  @csrf
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Cancha</label>
    <input id="id_cancha" name="id_cancha" type="text" class="form-control" tabindex="1">    
  </div>
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Hora</label>
    <input id="hora_turno" name="hora_turno" type="text" class="form-control" tabindex="2">    
  </div>
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Fecha</label>
    <input id="fecha_turno" name="fecha_turno" type="text" class="form-control" tabindex="3">    
  </div>
  <a href="/turnos" class="btn btn-secondary" tabindex="5">Cancelar</a>
  <button type="submit" class="btn btn-primary" tabindex="4">Guardar</button>
</form>
@endsection