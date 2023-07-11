<!DOCTYPE html>
<html>
<head>
    <title>Detalles de los turnos reservados</title>
</head>
<body>
    <h1>Detalles de los turnos reservados:</h1>

    <p>Correo electrónico: {{ $data['email'] }}</p>

    <h2>Detalle de la reserva:</h2>
    <ul>
        @foreach ($data['detalleReserva'] as $detalle)
            <li>{{ $detalle }}</li>
        @endforeach
    </ul>

    <p>Precio total: {{ $data['precio_total'] }}</p>
</body>
</html>
