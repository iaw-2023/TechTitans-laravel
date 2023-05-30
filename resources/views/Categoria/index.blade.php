@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('contenido')
<a style= "color:#ffffff;" href= "categorias/create" class="btn btn-primary">Crear categoria</a>

@if(session('success'))
    <div id="alert" class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div id="alert" class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="text-white">
<table id = categorias class="table table-dark table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorias as $categoria)
        <tr>
            <td>{{$categoria->id}}</td>
            <td>{{$categoria->nombre}}</td>
            <td>
                <form action="{{ route('categorias.destroy',$categoria->id) }}" method="POST">
                 <a href="/categorias/{{$categoria->id}}/edit" class="btn btn-info">Editar</a>         
                     @csrf
                     @method('DELETE')
                 <button type="submit" class="btn btn-danger">Delete</button>
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
        $('#categorias').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ]
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
@endsection