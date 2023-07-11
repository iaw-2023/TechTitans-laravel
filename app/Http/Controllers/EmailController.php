<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {   
        $client_email = $request->input('email');
            
        $data = [
            'email' => $request->input('email'),
            'detalleReserva' => $request->input('detalleReserva'),
            'precio_total' => $request->input('precio_total'),
        ];

        Mail::to($client_email)->send(new SendEmail($data));

        return response()->json(['message' => 'Mail sent succesfully.']);
    }
}