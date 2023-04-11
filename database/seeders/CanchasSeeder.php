<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanchasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [ 
            ['nombre' => 'Cancha 1',
             'valor_cancha' => 600,
             'techo' => false,
             'cant_jugadores' => 7,
             'superficie' => 'Cesped sintetico',
             'id_categoria' => 1
            ], 
            ['nombre' => 'Cancha 2',
             'valor_cancha' => 600,
             'techo' => false,
             'cant_jugadores' => 7,
             'superficie' => 'Cesped sintetico',
             'id_categoria' => 1
            ],
            ['nombre' => 'Cancha 3',
             'valor_cancha' => 1000,
             'techo' => true,
             'cant_jugadores' => 7,
             'superficie' => 'Cesped natural',
             'id_categoria' => 1
            ],
            ['nombre' => 'Cancha 1',
             'valor_cancha' => 2000,
             'techo' => false,
             'cant_jugadores' => 2,
             'superficie' => 'Polvo de ladrillo',
             'id_categoria' => 2
            ],
            ['nombre' => 'Cancha 2',
             'valor_cancha' => 2000,
             'techo' => false,
             'cant_jugadores' => 2,
             'superficie' => 'Polvo de ladrillo',
             'id_categoria' => 2
            ],
            ['nombre' => 'Cancha 1',
             'valor_cancha' => 9000,
             'techo' => true,
             'cant_jugadores' => 10,
             'superficie' => 'Parquet',
             'id_categoria' => 3
            ],    
            ['nombre' => 'Cancha 1',
             'valor_cancha' => 3000,
             'techo' => true,
             'cant_jugadores' => 4,
             'superficie' => 'Cemento',
             'id_categoria' => 4
            ],
            ['nombre' => 'Cancha 2',
             'valor_cancha' => 3000,
             'techo' => true,
             'cant_jugadores' => 4,
             'superficie' => 'Cemento',
             'id_categoria' => 4
            ],
            ['nombre' => 'Cancha 3',
             'valor_cancha' => 5000,
             'techo' => true,
             'cant_jugadores' => 4,
             'superficie' => 'Cesped sintetico',
             'id_categoria' => 4
            ],
            ['nombre' => 'Cancha 4',
             'valor_cancha' => 5000,
             'techo' => true,
             'cant_jugadores' => 4,
             'superficie' => 'Cesped sintetico',
             'id_categoria' => 4
            ],
            ['nombre' => 'Cancha 1',
             'valor_cancha' => 10000,
             'techo' => true,
             'cant_jugadores' => 14,
             'superficie' => 'Parquet',
             'id_categoria' => 5
            ],

        ];
        DB::table('canchas')->insert($data);
    }
}
