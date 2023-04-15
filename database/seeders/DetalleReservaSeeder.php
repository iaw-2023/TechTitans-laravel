<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetalleReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [ 
            ['precio' => 600.00,
             'id_reserva' => 1,
             'id_turno' => 1
            ],
            ['precio' => 2000.00,
             'id_reserva' => 1,
             'id_turno' => 4
            ],
            ['precio' => 2000.00,
             'id_reserva' => 2,
             'id_turno' => 5
            ],
            ['precio' => 600.00,
             'id_reserva' => 3,
             'id_turno' => 2
            ],
            ['precio' => 10000.00,
             'id_reserva' => 4,
             'id_turno' => 11
            ],
            ['precio' => 1000.00,
             'id_reserva' => 4,
             'id_turno' => 25
            ],
            ['precio' => 3000.00,
             'id_reserva' => 5,
             'id_turno' => 7
            ],
            ['precio' => 5000.00,
             'id_reserva' => 6,
             'id_turno' => 42
            ],
            ['precio' => 5000.00,
             'id_reserva' => 7,
             'id_turno' => 43
            ],
            ['precio' => 3000.00,
             'id_reserva' => 8,
             'id_turno' => 30
            ],
            ['precio' => 600.00,
             'id_reserva' => 9,
             'id_turno' => 23
            ],
            ['precio' => 600.00,
             'id_reserva' => 10,
             'id_turno' => 24
            ],
            ['precio' => 5000.00,
             'id_reserva' => 10,
             'id_turno' => 31
            ]
        ];
        DB::table('detalle_reservas')->insert($data);
    }
}