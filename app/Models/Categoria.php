<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public function getCancha(){
        return $this->hasMany(Cancha::class,'id_categoria','id');
    }
}