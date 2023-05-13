<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function turno()
    {
        return $this->hasMany(Turno::class);
    }
}
