<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotControllerAPI extends Controller
{
    public function handleChat(Request $request)
    {
        $userMessage = $request->input('message');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo', // Usa GPT-3.5-Turbo
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente útil para un sitio de reservas de deportes.'],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 200, // Ajusta según el tamaño de las respuestas
            'temperature' => 0.7, // Controla la creatividad
        ]);

        return response()->json([
            'reply' => $response->json('choices.0.message.content'),
        ]);
    }
}
