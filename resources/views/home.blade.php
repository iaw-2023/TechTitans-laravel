@extends('layouts.plantillabase')

@section('contenido')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Información del Clima</h4>
                </div>
                <div class="card-body">
                    @if(!isset($weatherData))
                        <div class="alert alert-warning">
                            <p>No se han cargado los datos del clima. Por favor, accede a través de la ruta correcta.</p>
                            <a href="{{ route('weather.index') }}" class="btn btn-primary">Ver información del clima</a>
                        </div>
                    @elseif(isset($weatherData['error']))
                        <div class="alert alert-danger">
                            {{ $weatherData['message'] }}
                        </div>
                    @else
                        <div class="row">
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
                                    <p>Actualizado: {{ \Carbon\Carbon::parse($weatherData['time'])->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Resto del código... -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection