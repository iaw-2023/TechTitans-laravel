<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Models\DetalleReserva;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CancelarReservasPendientes extends Command
{
    protected $signature = 'reservas:cancelar-pendientes';
    protected $description = 'Cancela reservas pendientes que no fueron pagadas en un tiempo determinado';

    public function handle()
    {
        $limite = Carbon::now()->subMinutes(15);

        $reservas = Reserva::where('estado', 'Pendiente')
            ->where('created_at', '<', $limite)
            ->get();

        foreach ($reservas as $reserva) {
            $reserva->estado = 'Cancelado';
            $reserva->updated_at = now();
            $reserva->save();

            // Marcar detalles como cancelados
            foreach ($reserva->detalle_reserva as $detalle) {
                $detalle->cancelado = true;
                $detalle->updated_at = now();
                $detalle->save();
            }

            Log::info("Reserva ID {$reserva->id} cancelada automáticamente por falta de pago.");
        }

        $this->info("Reservas pendientes verificadas. Total canceladas: " . $reservas->count());
    }
}
