<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\Turno;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {   
        $client_email = $request->input('email');
            
        $data = [
            'email' => $request->input('email'),
            'orderDetails' => $request->input('orderDetails'),
            'order_price' => $request->input('order_price'),
        ];

        Mail::to($client_email)->send(new SendEmail($data));

        return response()->json(['message' => 'Mail sent succesfully.']);
    }
}