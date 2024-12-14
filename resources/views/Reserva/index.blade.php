@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')
<div class="text-white">
    <table id="reservas" class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Email del cliente</th>
                <th scope="col">Estado</th>
                <th scope="col">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>{{$reserva->fecha_reserva}}</td>
                    <td>{{$reserva->hora_reserva}}</td>
                    <td>{{$reserva->email_cliente}}</td>
                    <td>{{$reserva->estado}}</td>
                    <td>
                        <form method="GET">
                            <a href="/reservas/show/{{$reserva->id}}" class="btn btn-primary">Ver detalle</a>         
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#reservas').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [
            {
                targets: 0,
                render: DataTable.render.date(),
            },
            ],
        });
    });
</script>
@endsection
@endsection
