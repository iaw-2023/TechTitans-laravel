<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
    ];

    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'id_cancha');
    }
    
    public function detalle_reserva()
    {
        return $this->hasOne(DetalleReserva::class, 'id_turno');
    }

    public function getDetalleReserva(){
        return $this->hasMany(DetalleReserva::class,'id_turno','id');
    }
}