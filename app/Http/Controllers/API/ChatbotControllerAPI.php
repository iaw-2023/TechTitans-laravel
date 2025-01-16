<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotControllerAPI extends Controller
{
    public function handleChat(Request $request)
    {
        $userMessage = $request->input('message');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente útil para un sitio de reservas de canchas para practicar deportes.'],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 200, // Ajusta según el tamaño de las respuestas
            'temperature' => 0.7, // Controla la creatividad
        ]);
        
        Log::info('esta es las respuestaaaaaaa ', $response->json());

        if ($response->failed()) {
            Log::error('Error en la API de OpenAI', ['response' => $response->json()]);
            return response()->json(['reply' => 'Hubo un problema al procesar tu solicitud.']);
        }

        $botMessage = $response->json('choices.0.message.content');

        if (empty($botMessage)) {
            return response()->json(['reply' => 'Lo siento, no pude procesar tu solicitud. Intenta de nuevo.']);
        }

        return response()->json(['reply' => $botMessage]);
    }
}
