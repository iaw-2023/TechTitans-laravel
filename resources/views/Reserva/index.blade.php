@extends('layouts.plantillabase')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endsection

@section('contenido')

@if (session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div id="error-alert" class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="text-white">
    <table id="reservas" class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Email del cliente</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('m/d/Y') }}</td>
                    <td>{{$reserva->hora_reserva}}</td>
                    <td>{{$reserva->email_cliente}}</td>
                    <td>{{$reserva->estado}}</td>
                    <td>
                        <a href="/reservas/show/{{$reserva->id}}" class="btn btn-primary btn-sm">Ver detalle</a>
                        @if (in_array($reserva->estado, ['Pendiente', 'Aceptado']))
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="{{ $reserva->id }}">
                                Cancelar 
                            </button>
                        @else
                            <button class="btn btn-danger btn-sm" disabled>Cancelar</button>
                        @endif
 
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar cancelación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas cancelar esta reserva? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
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

        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            var button = event.relatedTarget;
            // Extraer el ID de la reserva del atributo data-id
            var reservaId = button.getAttribute('data-id');
            // Actualizar la acción del formulario de eliminación dentro del modal
            var deleteForm = confirmDeleteModal.querySelector('#deleteForm');
            deleteForm.action = '/reservas/' + reservaId;
        });

        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            var errorAlert = document.getElementById('error-alert');

            if (successAlert) {
                successAlert.style.transition = "opacity 1s ease-out";
                successAlert.style.opacity = "0";
                setTimeout(function() {
                    successAlert.style.display = "none";
                }, 1000); // Espera 1 segundo para que la transición termine antes de ocultar
            }
        }, 3000); 
    });
</script>
@endsection
@endsection
