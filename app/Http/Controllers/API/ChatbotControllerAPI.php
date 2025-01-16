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
                ['role' => 'system', 'content' => 'Sos un asistente para un sitio de reservas de canchas. Responde preguntas sobre reservas, políticas, y disponibilidad de turnos de forma precisa y breve.
                -La reserva se confirma cuando el pago es confirmado.
                -Se aceptan tarjetas de debito y credito 12 cuotas s/interes y dinero en cuenta mediante MercadoPago.
                -No reembolsos'],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 50, // Ajusta según el tamaño de las respuestas
            'temperature' => 0.3, // Controla la creatividad
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
