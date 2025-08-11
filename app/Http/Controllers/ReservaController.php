<?php

namespace App\Http\Controllers;

use App\Models\DetalleReserva;
use App\Models\Reserva;
use App\Models\Turno;
use App\Models\Cancha;
use App\Models\Categoria;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'role:admin',
            'permission:crear reservas',
        ])->only(['create', 'store']);

        $this->middleware([
            'role:admin',
            'permission:eliminar reservas',
        ])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        return view('Reserva.index')->with('reservas', $reservas);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reserva = Reserva::find($id);
        if (!$reserva) {
            return redirect('/reservas')->with('error', 'Reserva no encontrada.');
        }
        $detalles = DetalleReserva::where('id_reserva', $id)->get();
        $turnos = [];
        foreach ($detalles as $detalle) {
            $turno = Turno::find($detalle->id_turno);
            if ($turno) {
                $turnos[] = $turno;
            }
        }
        return view('Reserva.show')->with('reserva', $reserva)->with('detalles', $detalles)->with('turnos', $turnos);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            session()->flash('error', 'Reserva no encontrada.');
            return redirect()->back();
        }

        // Only allow deletion if state is 'Pendiente' or 'Aceptado'
        if (!in_array($reserva->estado, ['Pendiente', 'Aceptado'])) {
            session()->flash('error', 'Solo se pueden eliminar reservas con estado "Pendiente" o "Aceptado".');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            // Obtener los detalles de la reserva antes de eliminarlos para enviar el email
            $detallesReserva = $reserva->detalle_reserva; // Usando detalle_reserva

            // Guardar el email del cliente para enviar la notificación
            $emailCliente = $reserva->email_cliente;

            // Eliminar los registros de DetalleReserva asociados primero
            $reserva->detalle_reserva()->delete(); // Usando detalle_reserva

            // Luego eliminar la Reserva
            $reserva->delete();

            DB::commit();

            // Enviar email de cancelación al cliente
            $this->enviarEmailCancelacion($emailCliente, $id, $detallesReserva);

            session()->flash('success', 'Reserva eliminada correctamente.');
            return redirect('/reservas');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting reservation ID {$id}: " . $e->getMessage());
            session()->flash('error', 'Hubo un error al eliminar la reserva. Por favor, inténtelo de nuevo.');
            return redirect()->back();
        }
    }

    public function cancelarPendientes()
    {
        $limite = now()->subMinutes(10);

        $reservas = Reserva::where('estado', 'Pendiente')
            ->where('created_at', '<', $limite)
            ->get();

        foreach ($reservas as $reserva) {
            $reserva->estado = 'Cancelado';
            $reserva->updated_at = now();
            $reserva->save();

            foreach ($reserva->detalle_reserva as $detalle) {
                $detalle->cancelado = true;
                $detalle->updated_at = now();
                $detalle->save();
            }

            Log::info("Reserva ID {$reserva->id} cancelada. Tiempo de espera agotado. (EasyCron) ");
             
            $emailCliente = $reserva->email_cliente; 

            if ($emailCliente) {

                $this->enviarEmailCancelacion($emailCliente, $reserva->id, $reserva->detalle_reserva);
            } else {
                Log::warning("No se pudo enviar email de cancelación. Reserva ID {$reserva->id} sin email_cliente.");
            }
        }

        return response()->json([
            'status' => 'ok',
            'canceladas' => $reservas->count()
        ]);
    }

    /**
     * Helper function to send cancellation email.
     * This logic is similar to what's in ReservaControllerAPI, adapted for this context.
     */
    private function enviarEmailCancelacion($emailCliente, $reservaId, $detallesReserva = null) {
        try {
            $emailController = new EmailController();

            // Si no se proporcionaron los detalles, obtenerlos
            if (!$detallesReserva || $detallesReserva->isEmpty()) {
                $detallesReserva = DetalleReserva::where('id_reserva', $reservaId)->get();
            }

            $detalle = [];
            $precioTotal = 0;

            foreach ($detallesReserva as $detalleReserva) {
                $turno = Turno::find($detalleReserva->id_turno);
                if ($turno) {
                    $cancha = Cancha::find($turno->id_cancha);
                    if ($cancha) {
                        $categoria = Categoria::find($cancha->id_categoria);
                        $detalle[] = [
                            'categoria' => $categoria ? $categoria->nombre : 'N/A',
                            'fecha' => Carbon::parse($turno->fecha_turno)->format('d/m/Y'), // Formatear fecha
                            'hora' => Carbon::parse($turno->hora_turno)->format('H:i'), // Formatear hora
                            'nombre_cancha' => $cancha->nombre,
                            'precio' => $cancha->precio,
                            'techo' => $cancha->techo,
                            'cant_jugadores' => $cancha->cant_jugadores,
                            'superficie' => $cancha->superficie,
                            'precio_total' => $detalleReserva->precio // Este es el precio del detalle, no el total de la reserva
                        ];
                        $precioTotal += $detalleReserva->precio;
                    }
                }
            }

            // Crear la solicitud para el controlador de email
            $requestData = [
                'email' => $emailCliente,
                'detalleReserva' => $detalle,
                'precio_total' => $precioTotal,
                'esCancelacion' => true // Indicar que es un email de cancelación
            ];

            // Usar Request::create para simular una petición POST
            $request = Request::create('/send-email', 'POST', $requestData);
            $emailController->sendEmail($request);

            Log::info('Email de cancelación enviado a: ' . $emailCliente . ' para reserva ID: ' . $reservaId);
        } catch (\Exception $e) {
            Log::error('Error al enviar email de cancelación para reserva ID: ' . $reservaId . ' - ' . $e->getMessage());
        }
    }
}