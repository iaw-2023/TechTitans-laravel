@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')
<a href= "turnos/create" class="btn btn-primary">Crear</a>

<div class="mb-3">
    <form method="POST" action="{{ route('turnos.index') }}">
      @csrf
      <label style="color: #ffffff;" for="fecha" class="form-label">Filtrar por fecha:</label>
      <input style="width : 150px; heigth : 10px"  type="date" id="fecha" name="fecha" class="form-control">
      <button style="color: #ffffff;" type="submit" class="btn btn-primary">Filtrar</button>
    </form>
  </div>

<table id="turnos" class="table table-dark table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Cancha</th>
            <th scope="col">Categoria</th>
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($turnos as $turno)
        <tr>
            <td>{{$turno->id}}</td>
            <td>{{$turno->cancha->nombre}}</td>  
            <td>{{$turno->cancha->categoria->nombre}}</td>            
            <td>{{$turno->fecha_turno}}</td>
            <td>{{$turno->hora_turno}}</td>
            <td>
                <form action="{{ route('turnos.destroy',$turno->id) }}" method="POST">
                 <a href="/turnos/{{$turno->id}}/edit" class="btn btn-info">Editar</a>         
                     @csrf
                     @method('DELETE')
                 <button type="submit" class="btn btn-danger">Delete</button>
                 <button type="button" class="btn btn-info">+Info</button>
                </form>          
               </td>  
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('js')
<style>
  
</style>
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
                targets: 3,
                render: DataTable.render.date(),
            },
        ],
        });
    });
</script>
@endsection