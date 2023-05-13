<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;
    public function cancha()
    {
        return $this->belongsTo(Cancha::class);
    }
    
    public function detalle_reserva()
    {
        return $this->hasOne(DetalleReserva::class);
    }
}