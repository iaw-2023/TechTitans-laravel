<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    use HasFactory;
    public function detalle_reserva(): HasMany
    {
        return $this->hasMany(DetalleReserva::class);
    }
}