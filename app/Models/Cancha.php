<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\HasMany;

class Cancha extends Model
{
    use HasFactory;

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function getTurnos(){
        return $this->hasMany(Turno::class,'id_cancha','id');
    }
}