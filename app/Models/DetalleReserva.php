<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_turno';
    protected $table = 'detalle_reservas';
    protected $fillable = [
        'precio',
        'id_reserva',
        'id_turno',
    ];

    /**
     * Get the reservation that owns the detail.
     */
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }

    /**
     * Get the turno associated with the detail.
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }
}