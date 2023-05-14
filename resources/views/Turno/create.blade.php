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
    <label style="color: #ffffff;" for="fecha_turno" class="form-label">Fecha</label>
    <input id="fecha_turno" name="fecha_turno" type="text" class="form-control" tabindex="3">
  </div>
  
  <a href="/turnos" class="btn btn-secondary" tabindex="5">Cancelar</a>
  <button type="submit" class="btn btn-primary" tabindex="4">Guardar</button>
</form>
@endsection

@section('js');
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>


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

<script>
  $(document).ready(function() {
    // Inicializar el datepicker en el campo de fecha_turno
    $('#fecha_turno').datepicker({
      dateFormat: 'yy-mm-dd', // Formato de la fecha (puedes ajustarlo según tus necesidades)
      changeMonth: true,
      changeYear: true
    });
  });
</script>
@endsection