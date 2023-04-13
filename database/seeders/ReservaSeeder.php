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
            ['fecha_reserva' => '2023/4/1',
             'hora_reserva' => '15:00:01',
             'email_cliente' => 'raul@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/2',
             'hora_reserva' => '15:50:00',
             'email_cliente' => 'gonzalo@gmail.com'
            ], 
            ['fecha_reserva' => '2023/4/2',
             'hora_reserva' => '15:00:00',
             'email_cliente' => 'patricio@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/2',
             'hora_reserva' => '15:59:00',
             'email_cliente' => 'pepito@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/3',
             'hora_reserva' => '19:00:00',
             'email_cliente' => 'juan@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'alberto@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'nicolas@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/3',
             'hora_reserva' => '20:00:00',
             'email_cliente' => 'miguel@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/5',
             'hora_reserva' => '17:56:00',
             'email_cliente' => 'dario@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/5',
             'hora_reserva' => '19:11:00',
             'email_cliente' => 'cristian@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/5',
             'hora_reserva' => '20:19:00',
             'email_cliente' => 'micaela@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/6',
             'hora_reserva' => '23:00:00',
             'email_cliente' => 'cristina@gmail.com'
            ],
            ['fecha_reserva' => '2023/4/7',
             'hora_reserva' => '22:00:00',
             'email_cliente' => 'valentina@gmail.com'
            ]
        ];
        DB::table('detallereservas')->insert($data);
    }
}

/*
            'fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 1
*/
