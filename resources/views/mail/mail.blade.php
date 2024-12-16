<!DOCTYPE html>
<html>
<head>
    <title>¡Mira el detalle de la reserva realizada!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles de la reserva</h1>
        <p>Gracias por reservar nuestras canchas. A continuación, te presentamos los detalles de tu reserva:</p>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre de la Cancha</th>
                    <th>Precio</th>
                    <th>Techo</th>
                    <th>Cantidad de Jugadores</th>
                    <th>Superficie</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['detalleReserva'] as $detalle)
                    <tr>
                        <td>{{ $detalle['categoria'] }}</td>
                        <td>{{ $detalle['fecha'] }}</td>
                        <td>{{ $detalle['hora'] }}</td>
                        <td>{{ $detalle['nombre_cancha'] }}</td>
                        <td>${{ $detalle['precio'] }}</td>
                        <td>{{ $detalle['techo'] ? 'Sí' : 'No' }}</td>
                        <td>{{ $detalle['cant_jugadores'] }}</td>
                        <td>{{ $detalle['superficie'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Precio Total: </strong>${{ $data['precio_total'] }}</p>
    </div>
</body>
</html>