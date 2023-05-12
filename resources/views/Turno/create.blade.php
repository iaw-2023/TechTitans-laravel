@extends('layouts.plantillabase')

@section('contenido')
<h2 style="color: #ffffff;" > CREAR TURNO</h2>

<form action="/turnos" method="POST">  
  @csrf
  <div class="mb-3">
    <label style="color:#ffffff" for="" class="form-label">Categoria</label>
    <select id="id_categoria" name="id_categoria" class="form-select" onchange="actualizarCanchas()">
      <option value="">Seleccionar categoría</option>
      @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
      @endforeach
    </select>    
  </div>
  
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Cancha</label>
    <select id="id_cancha" name="id_cancha" class="form-select"></select>
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

@section('js');
<script>
  var canchas = {!! json_encode($canchas) !!}; // Obtener las canchas directamente en el formato de JavaScript
  
  function actualizarCanchas() {
    var categoriaId = document.getElementById("id_categoria").value;
    var canchasSelect = document.getElementById("id_cancha");
    
    // Limpiar las opciones existentes
    canchasSelect.innerHTML = "";
    
    // Agregar las opciones correspondientes a las canchas asociadas a la categoría seleccionada
    canchas.forEach(function(cancha) {
      if (cancha.id_categoria == categoriaId) { // Verificar si la cancha pertenece a la categoría seleccionada
        var option = document.createElement("option");
        option.value = cancha.id;
        option.text = cancha.nombre;
        canchasSelect.appendChild(option);
      }
    });
  }
</script>
@endsection