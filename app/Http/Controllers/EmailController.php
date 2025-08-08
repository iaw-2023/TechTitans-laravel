<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function sendEmail(Request $request){
        $apikey =  env('BREVO_API_KEY');
        
        $data = [
            'detalleReserva' => $request->input('detalleReserva'),
            'precio_total' => $request->input('precio_total'),
            'esCancelacion' => $request->input('esCancelacion')
        ];

        // Determinar qué vista y asunto usar según el tipo de email
        $vista = $data['esCancelacion'] ? 'mail.cancelacion' : 'mail.mail';
        $asunto = $data['esCancelacion'] ? 'Confirmación de cancelación de reserva' : '¡Mirá el detalle de tu reserva!';
        
        try {
            $htmlContent = View::make($vista, compact('data'))->render();
            
            $response = Http::withHeaders([
                'api-key' => $apikey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false, // Deshabilitar la verificación SSL    
            ])->post('https://api.brevo.com/v3/smtp/emailCampaigns', [
                'sender' => [
                    'name' => 'Reserva Tu Cancha',
                    'email' => 'techtitaniaw@gmail.com',
                ],
                'to' => [
                    [
                        'email' => $request->input('email'),
                    ],
                ],
                'subject' => $asunto,
                'htmlContent' => $htmlContent,
            ]);

            if($response->successful()){
                return response()->json(['message' => 'Mail enviado correctamente.']);
            }
            else{
                Log::error('Error al enviar email: ' . $response->body());
                return response()->json(['message' => 'Error al enviar el mail.']);
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar email: ' . $e->getMessage());
            return response()->json(['message' => 'Error al enviar el mail: ' . $e->getMessage()], 500);
        }
    }
}