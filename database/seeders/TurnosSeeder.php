<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TurnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir las fechas de inicio y fin del rango de fechas
        $fecha_inicio = Carbon::parse('2023-05-01');
        $fecha_fin = Carbon::parse('2023-05-31');

        // Generar los turnos para cada día en el rango de fechas
        for ($fecha = $fecha_inicio; $fecha <= $fecha_fin; $fecha->addDay()) {
            // Generar los turnos para cada hora
            for ($hora = Carbon::parse('18:00:00'); $hora <= Carbon::parse('20:00:00'); $hora->addHours(1)) {
                for ($cancha = 1; $cancha <= 11; $cancha++) {
                    DB::table('turnos')->insert([
                      'fecha_turno' => $fecha,
                      'hora_turno' => $hora,
                      'id_cancha' => $cancha
                    ]);
                }
            }
        }     
    }
}