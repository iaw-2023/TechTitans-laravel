@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')
    <div class="container">
        <h2 style= "color:#ffffff;">Detalle de la reserva</h2>
        <table class="table" style= "color:#ffffff;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Email del Cliente</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $reserva->id }}</td>
                    <td>{{ $reserva->fecha_reserva }}</td>
                    <td>{{ $reserva->hora_reserva }}</td>
                    <td>{{ $reserva->email_cliente }}</td>
                </tr>
            </tbody>
        </table>

        
        <h2 style= "color:#ffffff;">Turnos asociados</h2>

        <table class="table" style= "color:#ffffff;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Categoria</th>
                    <th>Cancha</th>
                    <th>Superficie</th>
                    <th>Cant. Jugadores</th>
                    <th>Techada</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($turnos as $turno)
                    <tr>
                        <td>{{ $turno->fecha_turno }}</td>
                        <td>{{ $turno->hora_turno }}</td>
                        <td>{{$turno->cancha->categoria->nombre}}</td>
                        <td>{{$turno->cancha->nombre}}</td>  
                        <td>{{$turno->cancha->superficie}}</td>  
                        <td>{{$turno->cancha->cant_jugadores}}</td>  
                        <td>{{$turno->cancha->techo ? 'Si' : 'No'}}</td>  
                        <td>{{$turno->cancha->precio}}</td>      
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h5 style= "color:#ffffff;">Precio total: ${{ $detalles->first()->precio }}</h5>
        <div>
            <a href="/reservas" class="btn btn-info">Volver</a>
        </div>
    </div>
    

    @endsection
