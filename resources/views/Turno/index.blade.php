@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')
<a href= "turnos/create" class="btn btn-primary">Crear turno</a>

<div class="text-white">
<table id="turnos" class="table table-dark table-hover mt-4">
    <thead>
        <tr>
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
</div>
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
@endsection