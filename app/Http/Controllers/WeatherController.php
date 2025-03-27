<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;
    
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    
    /**
     * Mostrar la página principal con datos del clima
     */
    public function index()
    {
        $weatherData = $this->weatherService->getCurrentForecast();
        
        return view('home', compact('weatherData'));
    }
    
    /**
     * Método existente que podrías tener - asegúrate de que también pase los datos
     */
    public function getWeather()
    {
        $weatherData = $this->weatherService->getCurrentForecast();
        
        // Si esta función es para una API, devuelve JSON
        if (request()->wantsJson()) {
            return response()->json($weatherData);
        }
        
        // Si no, devuelve la vista con los datos
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
        
        $weatherData = $this->weatherService->getWeatherData(
            $validated['latitude'],
            $validated['longitude']
        );
        
        return response()->json($weatherData);
    }
}