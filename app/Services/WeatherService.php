<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHERMAP_API_KEY');
    }

    // Método para obtener el clima de Bahía Blanca por nombre de ciudad
    public function getCurrentWeatherByCity()
    {
        $response = Http::get("{$this->baseUrl}/weather", [
            'q' => 'Bahia Blanca,AR', // Ciudad y país
            'appid' => $this->apiKey,
            'units' => 'metric', // Para obtener las temperaturas en grados Celsius
            'lang' => 'es' // Para obtener la respuesta en español
        ]);

        if ($response->successful()) {
            $weatherData = $response->json();

            // Extraer información útil del JSON de la API
            $temperature = $weatherData['main']['temp'];
            $description = $weatherData['weather'][0]['description'];
            $humidity = $weatherData['main']['humidity'];
            $windSpeed = $weatherData['wind']['speed'];
            $windDirection = $weatherData['wind']['deg'];
            $pressure = $weatherData['main']['pressure'];
            $cityName = $weatherData['name'];
            $country = $weatherData['sys']['country'];

            // Obtener la hora local de Bahía Blanca
            $localTime = Carbon::now('America/Argentina/Buenos_Aires');

            // Estructurar los datos de manera clara
            return [
                'weather' => [
                    'city' => $cityName,
                    'country' => $country,
                    'temperature' => $temperature,
                    'description' => $description,
                    'humidity' => $humidity,
                    'wind_speed' => $windSpeed,
                    'wind_direction' => $windDirection,
                    'pressure' => $pressure,
                ],
                'localTime' => $localTime->format('H:i')
            ];
        } else {
            return [
                'error' => 'No se pudo obtener el clima',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }
    }
}
