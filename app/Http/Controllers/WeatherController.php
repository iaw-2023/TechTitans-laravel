<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Mostrar la página principal con datos del clima
     */
    public function index()
    {
        // Obtener datos del clima
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => -38.7196,
            'longitude' => -62.2724,
            'hourly' => 'temperature_2m,relative_humidity_2m,showers,precipitation_probability,precipitation',
        ]);
        
        $data = $response->json();
        
        // Obtener el índice de la hora actual
        $currentTime = now()->format('Y-m-d\TH:00');
        $timeIndex = array_search($currentTime, $data['hourly']['time'] ?? []);
        
        if ($timeIndex === false) {
            // Si no encontramos la hora exacta, usamos el primer elemento
            $timeIndex = 0;
        }
        
        // Extraer los datos actuales
        $weatherData = [
            'temperature' => $data['hourly']['temperature_2m'][$timeIndex] ?? null,
            'humidity' => $data['hourly']['relative_humidity_2m'][$timeIndex] ?? null,
            'precipitation_probability' => $data['hourly']['precipitation_probability'][$timeIndex] ?? null,
            'precipitation' => $data['hourly']['precipitation'][$timeIndex] ?? null,
            'showers' => $data['hourly']['showers'][$timeIndex] ?? null,
            'time' => $data['hourly']['time'][$timeIndex] ?? now()->format('Y-m-d\TH:00'),
            'full_data' => $data,
        ];
        
        return view('home', compact('weatherData'));
    }
    
    /**
     * Obtener datos del clima para una ubicación específica
     */
    public function getWeatherForLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'hourly' => 'temperature_2m,relative_humidity_2m,showers,precipitation_probability,precipitation',
        ]);
        
        return response()->json($response->json());
    }
    
    /**
     * Método existente que podrías tener
     */
    public function getWeather()
    {
        return $this->index();
    }
}