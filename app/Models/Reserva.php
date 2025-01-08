<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha_reserva',
        'hora_reserva',
        'email_cliente',
        'estado',
        'preference_id',
    ];

    public function detalle_reserva()
    {
        return $this->hasMany(DetalleReserva::class, 'id_reserva');
    }

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}