@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')

@can('crear turnos')
<a href= "turnos/create" class="btn btn-primary">Crear turno</a>
@endcan

@if(session('success'))
    <div id="alert" class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div id="alert" class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="text-white">
    <table id="turnos" class="table table-dark table-hover mt-4">
      <thead>
        <tr>
          <th scope="col">Cancha</th>
          <th scope="col">Categoria</th>
          <th scope="col">Fecha</th>
          <th scope="col">Hora</th>
          @can('eliminar turnos')
            <th scope="col">Acciones</th>
          @endcan  
        </tr>
      </thead>
      <tbody>
        @foreach ($turnos as $turno)
        <tr>
          <td>{{$turno->cancha->nombre}}</td>  
          <td>{{$turno->cancha->categoria->nombre}}</td>            
          <td>{{$turno->fecha_turno}}</td>
          <td>{{$turno->hora_turno}}</td>
          @can('eliminar turnos')
            <td>
              <form action="{{ route('turnos.destroy',$turno->id) }}" method="POST">
                <a href="/turnos/{{$turno->id}}/edit" class="btn btn-info">Editar</a>         
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal" data-id="{{$turno->cancha->id}}">Info cancha</button>
              </form>          
            </td>  
          @endcan
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@include('Modal.modal')
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#turnos').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [
            {
                targets: 2,
                render: DataTable.render.date(),
            },
        ],
        });
    });
</script>
<script>
  setTimeout(function() {
      var errorAlert = document.getElementById('alert');
      if (errorAlert) {
          errorAlert.style.transition = "opacity 1s";
          errorAlert.style.opacity = "0";
          setTimeout(function() {
              errorAlert.style.display = "none";
          }, 1000);
      }
  }, 4000); // Cambia el valor 4000 a la cantidad de milisegundos que deseas esperar antes de que la alerta desaparezca
</script>
<script>
  $(document).ready(function() {
    $('#miModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Botón que acciona el modal
      var canchaId = button.data('id'); // Obtener el ID de la cancha desde el botón

      // Hacer una petición AJAX para obtener los detalles de la cancha
      $.ajax({
        url: '/canchas/' + canchaId, // Ruta para obtener los detalles de la cancha por su ID
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          var cancha = response.cancha; // Datos de la cancha recibidos en la respuesta
          
          //convierto en texto los true y false
          var tActivo = cancha.activo ? 'Si' : 'No';
          var tTecho = cancha.techo ? 'Si' : 'No';
          //var tReservada = cancha.activo ? 'Si' : 'No';

          $('#nombreCancha').text(cancha.nombre); // Mostrar el nombre de la cancha en el modal
          $('#id').text(cancha.id);
          $('#activaCancha').text(tActivo);
          $('#categoriaCancha').text(cancha.categoria.nombre);
          $('#precioCancha').text(cancha.precio);
          $('#cantJugadoresCancha').text(cancha.cant_jugadores);
          $('#superficieCancha').text(cancha.superficie);
          $('#techoCancha').text(tTecho);
        },
        error: function(xhr, status, error) {
          console.log(error); // Manejo de errores en caso de que la petición falle
        }
      });
    });
  });
</script>

@endsection