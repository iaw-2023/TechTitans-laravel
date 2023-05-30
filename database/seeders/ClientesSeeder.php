<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nombre_usuario' => 'raul',
                'mail' => 'raul@gmail.com'
            ],
            [
                'nombre_usuario' => 'patricio',
                'mail' => 'patricio@gmail.com'
            ],
            [
                'nombre_usuario' => 'pepito',
                'mail' => 'pepito@gmail.com'
            ],
            [
                'nombre_usuario' => 'juan',
                'mail' => 'juan@gmail.com'
            ],
            [
                'nombre_usuario' => 'alberto',
                'mail' => 'alberto@gmail.com'
            ],
            [
                'nombre_usuario' => 'nicolas',
                'mail' => 'nicolas@gmail.com'
            ],
            [
                'nombre_usuario' => 'miguel',
                'mail' => 'miguel@gmail.com'
            ],
            [
                'nombre_usuario' => 'dario',
                'mail' => 'dario@gmail.com'
            ],
            [
                'nombre_usuario' => 'cristian',
                'mail' => 'cristian@gmail.com'
            ],
        ];
        DB::table('cliente')->insert($data);
    }
}