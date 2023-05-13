<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    use HasFactory;
    protected $table = 'detalle_reservas';
    
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}