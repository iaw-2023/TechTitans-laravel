@extends('layouts.plantillabase')

@section('contenido')
<h2 style= "color:#ffffff;">EDITAR TURNO</h2>

<form action="/turnos/{{$turno->id}}" method="POST">
    @csrf    
    @method('PUT')
  <div class="mb-3">
    <label style="color:#ffffff" for="" class="form-label">Categoria</label>
    <select id="id_categoria" name="id_categoria" class="form-select" onchange="actualizarCanchas()">
      @foreach ($categorias as $categoria)
          @if ($categoria->id == $turno->cancha->categoria->id)
              <option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>
          @else
              <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
          @endif
      @endforeach
  </select>
     
  </div>
  <div class="mb-3">
    <label style="color: #ffffff;" for="" class="form-label">Cancha</label>
    <select id="id_cancha" name="id_cancha" class="form-select">
      <option value="{{$turno->cancha->id}}">{{$turno->cancha->nombre}}</option>
      @foreach ($canchas as $cancha)
          @if ($cancha->categoria->id == $turno->cancha->categoria->id && $cancha->id != $turno->cancha->id)
              <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
          @endif
      @endforeach
  </select>
  
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