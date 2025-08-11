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
        return $this->hasMany(DetalleReserva::class, 'id_turno');
    }

    public function getDetalleReserva(){
        return $this->hasMany(DetalleReserva::class,'id_turno','id');
    }

    /**
     * Scope a query to only include available turns.
     * A turn is available if it's not associated with any 'Pendiente' or 'Aceptado' reservation.
     */
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('detalle_reserva', function ($query) {
            $query->whereHas('reserva', function ($query) {
                $query->whereIn('estado', ['Pendiente', 'Aceptado']);
            });
        });
    }
}