<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $baseUrl = 'https://api.open-meteo.com/v1/forecast';
    
    /**
     * Obtener datos del clima para una ubicación específica
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $hourlyParams
     * @return array
     */
    public function getWeatherData($latitude = -38.7196, $longitude = -62.2724, $hourlyParams = [])
    {
        // Parámetros por defecto si no se especifican
        if (empty($hourlyParams)) {
            $hourlyParams = [
                'temperature_2m',
                'relative_humidity_2m',
                'showers',
                'precipitation_probability',
                'precipitation',
                'wind_speed_10m'  // Añadimos velocidad del viento
            ];
        }
        
        $hourlyString = implode(',', $hourlyParams);
        
        // Usar caché para evitar llamadas repetidas a la API (30 minutos)
        $cacheKey = "weather_data_{$latitude}_{$longitude}";
        
        return Cache::remember($cacheKey, 1800, function () use ($latitude, $longitude, $hourlyString) {
            $response = Http::withoutVerifying()->get($this->baseUrl, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'hourly' => $hourlyString,
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'error' => true,
                'message' => 'No se pudo obtener la información del clima',
                'status' => $response->status()
            ];
        });
    }
    
    /**
     * Obtener el pronóstico actual
     *
     * @return array
     */
    public function getCurrentForecast()
    {
        $data = $this->getWeatherData();
        
        if (isset($data['error'])) {
            return $data;
        }
        
        // Obtener el índice de la hora actual
        $currentTime = now()->format('Y-m-d\TH:00');
        $timeIndex = array_search($currentTime, $data['hourly']['time'] ?? []);
        
        if ($timeIndex === false) {
            // Si no encontramos la hora exacta, buscar la hora más cercana
            $now = now();
            $closestDiff = PHP_INT_MAX;
            $closestIndex = 0;
            
            foreach ($data['hourly']['time'] as $index => $timeStr) {
                $time = \Carbon\Carbon::parse($timeStr);
                $diff = abs($now->diffInSeconds($time));
                
                if ($diff < $closestDiff) {
                    $closestDiff = $diff;
                    $closestIndex = $index;
                }
            }
            
            $timeIndex = $closestIndex;
        }
        
        // Extraer los datos actuales
        return [
            'temperature' => $data['hourly']['temperature_2m'][$timeIndex] ?? null,
            'humidity' => $data['hourly']['relative_humidity_2m'][$timeIndex] ?? null,
            'precipitation_probability' => $data['hourly']['precipitation_probability'][$timeIndex] ?? null,
            'precipitation' => $data['hourly']['precipitation'][$timeIndex] ?? null,
            'showers' => $data['hourly']['showers'][$timeIndex] ?? null,
            'wind_speed' => $data['hourly']['wind_speed_10m'][$timeIndex] ?? null,
            'time' => $data['hourly']['time'][$timeIndex] ?? now()->format('Y-m-d\TH:00'),
            'current_index' => $timeIndex,  // Añadimos el índice actual
            'full_data' => $data,
        ];
    }
}