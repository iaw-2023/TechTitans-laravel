<!DOCTYPE html>
<html>
<head>
    <title>Clima en Bahía Blanca</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Clima en Bahía Blanca</h1>
            
            @if(isset($weather['main']))
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Temperatura:</span>
                        <span class="font-semibold">{{ $weather['main']['temp'] }}°C</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Descripción:</span>
                        <span class="font-semibold">{{ $weather['weather'][0]['description'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Hora local:</span>
                        <span class="font-semibold">{{ $localTime }}</span>
                    </div>
                </div>
            @else
                <p class="text-red-500">No se pudo obtener la información del clima.</p>
            @endif
        </div>
    </div>
</body>
</html>