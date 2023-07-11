<!DOCTYPE html>
<html>
<head>
    <title>¡Mira el detalle de la reserva realizada!</title>
</head>
<body>
    <img src="{{ asset('/images/logo.png') }}" alt="Icono de correo electrónico">
    <h2>Detalle de la reserva:</h2>
    <ul>
        @foreach ($data['detalleReserva'] as $detalle)
            <li>
                <strong>Fecha:</strong> {{ $detalle['fecha'] }}<br>
                <strong>Hora:</strong> {{ $detalle['hora'] }}<br>
                <strong>Categoría:</strong> {{ $detalle['categoria'] }}<br>
                <strong> {{ $detalle['nombre_cancha'] }}<br></strong>
                <strong>Cantidad de jugadores:</strong> {{ $detalle['cant_jugadores'] }}<br>
                <strong>Techo:</strong> {{ $detalle['techo'] ? 'Si' : 'No'}}<br>
                <strong>Superficie:</strong> {{ $detalle['superficie'] }}<br>
                <strong>Precio:</strong> ${{ $detalle['precio'] }}<br>
            </li>
        @endforeach
    </ul>

    <strong><h3>Precio total: ${{ $data['precio_total'] }}</h3></strong>
</body>
</html>
