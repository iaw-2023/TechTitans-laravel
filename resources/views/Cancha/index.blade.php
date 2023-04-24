@extends('layouts.plantillabase')

@section('contenido')
<a href= "canchas/create" class="btn btn-primary">Crear</a>

<table class="table table-dark table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Precio</th>
            <th scope="col">Techo</th>
            <th scope="col">Cant. Jugadores</th>
            <th scope="col">Superficie</th>
            <th scope="col">ID Categoria</th>
            <th scope="col">Activo</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($canchas as $cancha)
        <tr>
            <td>{{$cancha->id}}</td>
            <td>{{$cancha->nombre}}</td>
            <td>{{$cancha->precio}}</td>
            <td>{{$cancha->techo}}</td>
            <td>{{$cancha->cant_jugadores}}</td>
            <td>{{$cancha->superficie}}</td>
            <td>{{$cancha->id_categoria}}</td>
            <td>{{$cancha->activo}}</td>
            <td>
                <form action="{{ route('canchas.destroy',$cancha->id) }}" method="POST">
                 <a href="/canchas/{{$cancha->id}}/edit" class="btn btn-info">Editar</a>         
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