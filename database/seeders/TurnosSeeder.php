<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [ 
            //cancha 1 de futbol 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 1
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 1
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 1
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 1
            ], 
            //cancha 2 de futbol
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 2
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 2
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 2
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 2
            ],
            //cancha 3 de futbol
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 3
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 3
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 3
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 3
            ], 
            //cancha 1 de tenis 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 4
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 4
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 4
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 4
            ], 
            //cancha 2 de tenis 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 5
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 5
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 5
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 5
            ], 
            //cancha de basquet
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 6
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 6
            ],
            //cancha 1 de padell 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 7
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 7
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 7
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 7
            ], 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '22:00:00',
             'id_cancha' => 8
            ],
            //cancha 2 de padell 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 8
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 8
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 8
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 8
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '22:00:00',
             'id_cancha' => 8
            ],
            //cancha 3 de padell 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 9
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 9
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 9
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 9
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '22:00:00',
             'id_cancha' => 9
            ],
            //cancha 4 de padell 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '18:00:00',
             'id_cancha' => 10
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '19:00:00',
             'id_cancha' => 10
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 10
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 10
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '22:00:00',
             'id_cancha' => 10
            ],
            //cancha de handball
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '20:00:00',
             'id_cancha' => 11
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '21:00:00',
             'id_cancha' => 11
            ], 
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '22:00:00',
             'id_cancha' => 11
            ],
            ['fecha_turno' => '2023/4/11',
             'hora_turno' => '23:00:00',
             'id_cancha' => 11
            ]
        ];
        DB::table('turnos')->insert($data);
    }
}
