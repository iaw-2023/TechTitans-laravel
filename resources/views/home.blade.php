@extends('layouts.plantillabase')

@section('contenido')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Clima en Bahía Blanca</h4>
                    <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
                </div>
                <div class="card-body">
                    @if(!isset($weatherData))
                        <div class="alert alert-warning">
                            <p>No se han cargado los datos del clima. Por favor, accede a través de la ruta correcta.</p>
                            <a href="/weather" class="btn btn-primary">Ver información del clima</a>
                        </div>
                    @elseif(isset($weatherData['error']))
                        <div class="alert alert-danger">
                            {{ $weatherData['message'] }}
                        </div>
                    @else
                        <!-- Condiciones Actuales -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5>Condiciones Actuales</h5>
                                    <p class="fs-1 fw-bold">{{ $weatherData['temperature'] }}°C</p>
                                    <p>Humedad: {{ $weatherData['humidity'] }}%</p>
                                    <p>Probabilidad de precipitación: {{ $weatherData['precipitation_probability'] }}%</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h5>Detalles</h5>
                                    <p>Precipitación: {{ $weatherData['precipitation'] }} mm</p>
                                    <p>Lluvias: {{ $weatherData['showers'] }} mm</p>
                                    @if(isset($weatherData['wind_speed']))
                                        <p>Viento: {{ $weatherData['wind_speed'] }} km/h</p>
                                    @elseif(isset($weatherData['full_data']['hourly']['wind_speed_10m'][$weatherData['current_index'] ?? 0]))
                                        <p>Viento: {{ $weatherData['full_data']['hourly']['wind_speed_10m'][$weatherData['current_index'] ?? 0] }} km/h</p>
                                    @endif
                                    <p>Actualizado: {{ \Carbon\Carbon::parse($weatherData['time'])->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Navegación de pestañas -->
                        <ul class="nav nav-tabs" id="weatherTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="hourly-tab" data-bs-toggle="tab" data-bs-target="#hourly" type="button" role="tab" aria-controls="hourly" aria-selected="true">Por Hora</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab" aria-controls="daily" aria-selected="false">Por Día</button>
                            </li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="weatherTabsContent">
                            <!-- Pronóstico por Hora -->
                            <div class="tab-pane fade show active" id="hourly" role="tabpanel" aria-labelledby="hourly-tab">
                                <div class="mt-3">
                                    <h5>Pronóstico por Hora</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Hora</th>
                                                    <th>Temperatura</th>
                                                    <th>Humedad</th>
                                                    <th>Prob. Lluvia</th>
                                                    <th>Precipitación</th>
                                                    @if(isset($weatherData['full_data']['hourly']['wind_speed_10m']))
                                                        <th>Viento</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    // Obtener el índice actual y mostrar las próximas 24 horas
                                                    $currentIndex = $weatherData['current_index'] ?? 0;
                                                    $hoursToShow = 24;
                                                @endphp

                                                @for ($i = $currentIndex; $i < $currentIndex + $hoursToShow && $i < count($weatherData['full_data']['hourly']['time']); $i++)
                                                    @php
                                                        $time = \Carbon\Carbon::parse($weatherData['full_data']['hourly']['time'][$i]);
                                                        $isCurrentHour = $i === $currentIndex;
                                                    @endphp
                                                    <tr class="{{ $isCurrentHour ? 'table-primary' : '' }}">
                                                        <td>{{ $time->format('d/m H:i') }}</td>
                                                        <td>{{ $weatherData['full_data']['hourly']['temperature_2m'][$i] }}°C</td>
                                                        <td>{{ $weatherData['full_data']['hourly']['relative_humidity_2m'][$i] }}%</td>
                                                        <td>{{ $weatherData['full_data']['hourly']['precipitation_probability'][$i] }}%</td>
                                                        <td>{{ $weatherData['full_data']['hourly']['precipitation'][$i] }} mm</td>
                                                        @if(isset($weatherData['full_data']['hourly']['wind_speed_10m']))
                                                            <td>{{ $weatherData['full_data']['hourly']['wind_speed_10m'][$i] }} km/h</td>
                                                        @endif
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Pronóstico por Día -->
                            <div class="tab-pane fade" id="daily" role="tabpanel" aria-labelledby="daily-tab">
                                <div class="mt-3">
                                    <h5>Pronóstico por Día</h5>
                                    <div class="row">
                                        @php
                                            // Agrupar datos por día
                                            $dailyData = [];
                                            $currentDay = '';
                                            $dayIndex = -1;
                                            
                                            foreach ($weatherData['full_data']['hourly']['time'] as $index => $timeStr) {
                                                $time = \Carbon\Carbon::parse($timeStr);
                                                $day = $time->format('Y-m-d');
                                                
                                                if ($day !== $currentDay) {
                                                    $currentDay = $day;
                                                    $dayIndex++;
                                                    $dailyData[$dayIndex] = [
                                                        'date' => $time->format('d/m/Y'),
                                                        'day' => $time->locale('es')->isoFormat('dddd'),
                                                        'temp_min' => $weatherData['full_data']['hourly']['temperature_2m'][$index],
                                                        'temp_max' => $weatherData['full_data']['hourly']['temperature_2m'][$index],
                                                        'humidity_avg' => $weatherData['full_data']['hourly']['relative_humidity_2m'][$index],
                                                        'precip_prob_max' => $weatherData['full_data']['hourly']['precipitation_probability'][$index],
                                                        'precipitation_total' => $weatherData['full_data']['hourly']['precipitation'][$index],
                                                        'count' => 1
                                                    ];
                                                } else {
                                                    // Actualizar min/max/avg
                                                    $dailyData[$dayIndex]['temp_min'] = min($dailyData[$dayIndex]['temp_min'], $weatherData['full_data']['hourly']['temperature_2m'][$index]);
                                                    $dailyData[$dayIndex]['temp_max'] = max($dailyData[$dayIndex]['temp_max'], $weatherData['full_data']['hourly']['temperature_2m'][$index]);
                                                    $dailyData[$dayIndex]['humidity_avg'] += $weatherData['full_data']['hourly']['relative_humidity_2m'][$index];
                                                    $dailyData[$dayIndex]['precip_prob_max'] = max($dailyData[$dayIndex]['precip_prob_max'], $weatherData['full_data']['hourly']['precipitation_probability'][$index]);
                                                    $dailyData[$dayIndex]['precipitation_total'] += $weatherData['full_data']['hourly']['precipitation'][$index];
                                                    $dailyData[$dayIndex]['count']++;
                                                }
                                            }
                                            
                                            // Calcular promedios
                                            foreach ($dailyData as $i => $day) {
                                                $dailyData[$i]['humidity_avg'] = round($day['humidity_avg'] / $day['count']);
                                            }
                                        @endphp

                                        @foreach ($dailyData as $day)
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong>{{ $day['date'] }}</strong> - {{ ucfirst($day['day']) }}
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h5 class="card-title">{{ $day['temp_min'] }}° / {{ $day['temp_max'] }}°</h5>
                                                                <p class="card-text">Humedad: {{ $day['humidity_avg'] }}%</p>
                                                            </div>
                                                            <div class="text-end">
                                                                <p class="card-text">Prob. Lluvia: {{ $day['precip_prob_max'] }}%</p>
                                                                <p class="card-text">Precipitación: {{ number_format($day['precipitation_total'], 1) }} mm</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Consulta de otra ubicación -->
                        <div class="mt-4">
                            <h5>Consultar otra ubicación</h5>
                            <form id="weatherForm" class="row g-3">
                                <div class="col-md-5">
                                    <label for="latitude" class="form-label">Latitud</label>
                                    <input type="number" step="0.0001" class="form-control" id="latitude" name="latitude" value="-38.7196" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="longitude" class="form-label">Longitud</label>
                                    <input type="number" step="0.0001" class="form-control" id="longitude" name="longitude" value="-62.2724" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Consultar</button>
                                </div>
                            </form>
                            <div id="weatherResult" class="mt-3"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const weatherForm = document.getElementById('weatherForm');
        const weatherResult = document.getElementById('weatherResult');

        if (weatherForm) {
            weatherForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;
                
                // Mostrar indicador de carga
                weatherResult.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
                
                // Enviar solicitud AJAX
                fetch('/weather/location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        weatherResult.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    } else {
                        // Obtener el índice de la hora actual
                        const currentTime = new Date().toISOString().split('T')[0] + 'T' + new Date().getHours() + ':00';
                        let timeIndex = data.hourly.time.indexOf(currentTime);
                        
                        if (timeIndex === -1) {
                            timeIndex = 0;
                        }
                        
                        // Mostrar resultados
                        weatherResult.innerHTML = `
                            <div class="alert alert-success">
                                <h5>Resultados para: ${latitude}, ${longitude}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="fs-1 fw-bold">${data.hourly.temperature_2m[timeIndex]}°C</p>
                                        <p>Humedad: ${data.hourly.relative_humidity_2m[timeIndex]}%</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Probabilidad de precipitación: ${data.hourly.precipitation_probability[timeIndex]}%</p>
                                        <p>Precipitación: ${data.hourly.precipitation[timeIndex]} mm</p>
                                        ${data.hourly.wind_speed_10m ? `<p>Viento: ${data.hourly.wind_speed_10m[timeIndex]} km/h</p>` : ''}
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p>Para ver el pronóstico completo, <a href="/weather" class="alert-link">actualiza la página</a>.</p>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    weatherResult.innerHTML = `<div class="alert alert-danger">Error al obtener datos: ${error.message}</div>`;
                });
            });
        }
    });
</script>
@endsection