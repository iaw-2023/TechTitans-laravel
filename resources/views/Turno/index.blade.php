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
          <td>{{ \Carbon\Carbon::parse($turno->fecha_turno)->addDay()->format('Y-m-d') }}</td>
          <td>{{$turno->hora_turno}}</td>
          @can('eliminar turnos')
            <td>
              <form action="{{ route('turnos.destroy',$turno->id) }}" method="POST">
                <a href="/turnos/{{$turno->id}}/edit" class="btn btn-info">Editar</a>         
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
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
  }, 4000);
</script>
<script>
  $(document).ready(function() {
    // Caché para almacenar datos de canchas
    var canchaCache = {};
    
    // Precargar datos de canchas para todos los botones en la página
    var canchaIds = [];
    $('[data-bs-target="#miModal"]').each(function() {
        var canchaId = $(this).data('id');
        if (canchaId && !canchaCache[canchaId] && canchaIds.indexOf(canchaId) === -1) {
            canchaIds.push(canchaId);
        }
    });
    
    // Si hay IDs para precargar, hacerlo en una sola petición
    if (canchaIds.length > 0) {
        // Mostrar un indicador de carga sutil
        var loadingIndicator = $('<div class="position-fixed bottom-0 end-0 p-3 text-info">Cargando datos...</div>');
        $('body').append(loadingIndicator);
        
        // Precargar todos los datos de canchas necesarios
        $.ajax({
            url: '/api/canchas-batch',
            type: 'POST',
            data: { ids: canchaIds },
            dataType: 'json',
            success: function(response) {
                // Guardar todas las canchas en caché
                if (response.canchas) {
                    response.canchas.forEach(function(cancha) {
                        canchaCache[cancha.id] = cancha;
                    });
                }
                loadingIndicator.remove();
            },
            error: function() {
                loadingIndicator.remove();
                // Si falla la precarga, seguiremos con el método individual
            }
        });
    }
    
    // Cuando se abre el modal
    $('#miModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var canchaId = button.data('id');
        var modal = $(this);
        
        // Mostrar un indicador de carga en el modal
        modal.find('.modal-body').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
        
        // Si ya tenemos los datos en caché, mostrarlos inmediatamente
        if (canchaCache[canchaId]) {
            updateModalContent(modal, canchaCache[canchaId]);
            return;
        }
        
        // Si no están en caché, hacer la petición AJAX
        $.ajax({
            url: '/canchas/' + canchaId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Guardar en caché
                canchaCache[canchaId] = response.cancha;
                updateModalContent(modal, response.cancha);
            },
            error: function(xhr, status, error) {
                modal.find('.modal-body').html('<div class="alert alert-danger">Error al cargar los datos</div>');
                console.log(error);
            }
        });
    });
    
    function updateModalContent(modal, cancha) {
        var tActivo = cancha.activo ? 'Si' : 'No';
        var tTecho = cancha.techo ? 'Si' : 'No';
        
        // Actualizar el contenido del modal
        var content = `
            <div class="container">
                <div class="row mb-2">
                    <div class="col-4 fw-bold">ID:</div>
                    <div class="col-8">${cancha.id}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Nombre:</div>
                    <div class="col-8">${cancha.nombre}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Activa:</div>
                    <div class="col-8">${tActivo}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Categoría:</div>
                    <div class="col-8">${cancha.categoria.nombre}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Precio:</div>
                    <div class="col-8">${cancha.precio}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Jugadores:</div>
                    <div class="col-8">${cancha.cant_jugadores}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Superficie:</div>
                    <div class="col-8">${cancha.superficie}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-4 fw-bold">Techo:</div>
                    <div class="col-8">${tTecho}</div>
                </div>
            </div>
        `;
        
        modal.find('.modal-body').html(content);
    }
  });
</script>

@endsection