<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Cancelación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #f44336;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
        .total {
            font-weight: bold;
            text-align: right;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmación de Cancelación de Reserva</h1>
    </div>
    
    <div class="content">
        <p>Estimado cliente,</p>
        
        <p>Le confirmamos que su reserva ha sido cancelada exitosamente. A continuación, encontrará el detalle de los turnos que fueron cancelados:</p>
        
        <table>
            <thead>
                <tr>
                    <th>Cancha</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['detalleReserva'] as $detalle)
                <tr>
                    <td>{{ $detalle['nombre_cancha'] }}</td>
                    <td>{{ $detalle['categoria'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($detalle['fecha'])->format('d/m/Y') }}</td>
                    <td>{{ $detalle['hora'] }}</td>
                    <td>${{ $detalle['precio'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            <p>Monto total reembolsado: ${{ $data['precio_total'] }}</p>
        </div>
        
        <p>Si tiene alguna pregunta o necesita asistencia adicional, no dude en contactarnos.</p>
        
        <p>Gracias por utilizar nuestro servicio.</p>
        
        <p>Atentamente,<br>
        Equipo de Reserva Tu Cancha</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo electrónico automático, por favor no responda a este mensaje.</p>
    </div>
</body>
</html>