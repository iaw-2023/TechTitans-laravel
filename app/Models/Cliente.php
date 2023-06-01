<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'cliente';
    protected $fillable = [
        'nombre_usuario',
        'mail',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'email_cliente', 'mail');
    }
}
