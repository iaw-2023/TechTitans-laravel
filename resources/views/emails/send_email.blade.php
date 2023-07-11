<!DOCTYPE html>
<html>
<head>
    <title>¡Mira el detalle de la reserva realizada!</title>
</head>
<body>
    <h2>Detalle de la reserva:</h2>
    <ul>
        @foreach ($data['detalleReserva'] as $detalle)
            <li>
                <strong>Categoría:</strong> {{ $detalle['categoria'] }}<br>
                <strong>Fecha:</strong> {{ $detalle['fecha'] }}<br>
                <strong>Hora:</strong> {{ $detalle['hora'] }}<br>
                <strong>Nombre de la cancha:</strong> {{ $detalle['nombre_cancha'] }}<br>
                <strong>Precio:</strong> {{ $detalle['precio'] }}<br>
                <strong>Techo:</strong> {{ $detalle['techo'] }}<br>
                <strong>Cantidad de jugadores:</strong> {{ $detalle['cant_jugadores'] }}<br>
                <strong>Superficie:</strong> {{ $detalle['superficie'] }}<br>
                <strong>Precio total:</strong> {{ $detalle['precio_total'] }}
            </li>
        @endforeach
    </ul>

    <p>Precio total: ${{ $data['precio_total'] }}</p>
</body>
</html>
