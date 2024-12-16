<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [ 
            ['fecha_reserva' => '2025/4/1',
             'hora_reserva' => '15:00:01',
             'email_cliente' => 'raul@gmail.com',
             'estado' => 'Pendiente'
            ],
            ['fecha_reserva' => '2025/4/2',
             'hora_reserva' => '15:50:00',
             'email_cliente' => 'raul@gmail.com',
             'estado' => 'Aceptado'
            ], 
            ['fecha_reserva' => '2025/4/2',
             'hora_reserva' => '15:00:00',
             'email_cliente' => 'patricio@gmail.com',
             'estado' => 'Rechazado'
            ],
            ['fecha_reserva' => '2025/4/2',
             'hora_reserva' => '15:59:00',
             'email_cliente' => 'pepito@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/3',
             'hora_reserva' => '19:00:00',
             'email_cliente' => 'juan@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'alberto@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'nicolas@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'miguel@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/5',
             'hora_reserva' => '17:56:00',
             'email_cliente' => 'dario@gmail.com',
             'estado' => 'Aceptado'
            ],
            ['fecha_reserva' => '2025/4/5',
             'hora_reserva' => '19:11:00',
             'email_cliente' => 'cristian@gmail.com',
             'estado' => 'Aceptado'
            ]
        ];
        DB::table('reservas')->insert($data);
    }
}