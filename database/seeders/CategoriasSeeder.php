<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [ 
            ['nombre' => 'Futbol'], 
            ['nombre' => 'Tenis'], 
            ['nombre' => 'Basquet'],
            ['nombre' => 'Paddel'],
            ['nombre' => 'Handball']
            ];
        DB::table('categorias')->insert($data);
    }
}
