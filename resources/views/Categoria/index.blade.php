@extends('layouts.plantillabase')

@section('contenido')
<a href= "categorias/create" class="btn btn-primary">Crear</a>

<table class="table table-dark table-striped mt-4">
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
@endsection