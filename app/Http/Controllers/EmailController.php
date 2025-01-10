<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class EmailController extends Controller
{
    public function sendEmail(Request $request){
        $apikey =  env('BREVO_API_KEY');
        
        $data = [
            'detalleReserva' => $request->input('detalleReserva'),
            'precio_total' => $request->input('precio_total')
        ];

        $htmlContent = View::make('mail.mail', compact('data'))->render();
        
        $response = Http::withHeaders([
            'api-key' => $apikey,
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false, // Deshabilitar la verificación SSL    
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => 'Reserva Tu Cancha',
                'email' => 'techtitaniaw@gmail.com',
            ],
            'to' => [
                [
                    'email' => $request->input('email'),
                ],
            ],
            'subject' => '¡Mirá el detalle de tu reserva!',
            'htmlContent' => $htmlContent,
        ]);

        if($response->successful()){
            return response()->json(['message' => 'Mail enviado correctamente.']);
    }
    else{
        return response()->json(['message' => 'Error al enviar el mail.']);
    }
    }
}