@extends('layouts.plantillabase');

@section('contenido')
<h2 style= "color:#ffffff;">CREAR CANCHA</h2>

<form action="/canchas" method="POST">  
  @csrf
  <div class="mb-3">
    <label style= "color:#ffffff;" for="" class="form-label">Nombre</label>
    <input id="nombre" name="nombre" type="text" class="form-control" tabindex="1">    
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="" class="form-label">Precio</label>
    <input id="precio" name="precio" type="text" class="form-control" tabindex="2">    
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="techo" class="form-label">Techo</label>
    <select id="techo" name="techo" class="form-select" tabindex="3">
        <option value="si">Si</option>
        <option value="no">No</option>
    </select>
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="" class="form-label">Cant. Jugadores</label>
    <input id="cant_jugadores" name="cant_jugadores" type="text" class="form-control" tabindex="4">    
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="" class="form-label">Superficie</label>
    <input id="superficie" name="superficie" type="text" class="form-control" tabindex="5">    
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="" class="form-label">ID Categoria</label>
    <input id="id_categoria" name="id_categoria" type="text" class="form-control" tabindex="6">    
  </div>
  <div class="mb-3">
    <label style= "color:#ffffff;" for="activo" class="form-label">Activo</label>
    <select id="activo" name="activo" class="form-select" tabindex="7">
        <option value="si">Si</option>
        <option value="no">No</option>
    </select>
  </div>
  <a href="/canchas" class="btn btn-secondary" tabindex="9">Cancelar</a>
  <button type="submit" class="btn btn-primary" tabindex="8">Guardar</button>
</form>
@endsection