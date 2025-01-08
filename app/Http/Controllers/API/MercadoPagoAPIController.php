<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Turno;
use App\Models\Cancha;
use App\Models\DetalleReserva;
use MercadoPago\SDK;
use MercadoPago\Item;
use MercadoPago\Preference;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoAPIController extends Controller
{
    public function createPreference(Request $request, $reserva_id)
    {
        try {
            // Inicializar el SDK con el token de acceso
            SDK::setAccessToken(env('MP_TOKEN'));

            Log::info('Iniciando creación de preferencia para la reserva ID: ' . $reserva_id);

            $reserva = Reserva::findOrFail($reserva_id);
            Log::info('Reserva encontrada: ', $reserva->toArray());

            if ($reserva->preference_id) {
                Log::info('Preferencia ya existente: ' . $reserva->preference_id);
                return response()->json(['preference_id' => $reserva->preference_id], 200);
            }

            $items = array();
            $detalles = $reserva->detalle_reserva()->get();
            Log::info('Detalles de la reserva: ', $detalles->toArray());

            foreach ($detalles as $detalle) {
                $turno = Turno::find($detalle->id_turno); // Busca el turno correspondiente
                $cancha = Cancha::find($turno->id_cancha); // Busca la cancha asociada
            
                $item = new \MercadoPago\Item();
                $item->title = "Reserva de Cancha - " . $cancha->nombre; // Nombre del item
                $item->description = "Turno: " . $turno->hora_turno . " | Fecha: " . $turno->fecha_turno; // Detalle del turno
                $item->quantity = 1; // Siempre es 1, ya que son turnos individuales
                $item->unit_price = (float) $detalle->precio; // Asegúrate de usar el precio correcto
                $item->currency_id = "ARS"; // Moneda
            
                $items[] = $item;
            }

            $preference = new Preference();
            $preference->items = $items;
            $preference->back_urls = [
                "success" => "http://localhost:3000/",
                "pending" => "http://localhost:3000/pending",
                "failure" => "http://localhost:3000/failure"
            ];
            $preference->auto_return = "approved";
            $preference->external_reference = $reserva_id;
            $preference->notification_url = "http://localhost:8000/api/mercadopago/notify?source_news=ipn";
            $preference->save();

            Log::info('Preferencia creada: ', (array) $preference);

            $reserva->preference_id = $preference->id;
            $reserva->save();

            Log::info('Preferencia guardada en la reserva: ' . $reserva->preference_id);

            return response()->json(['preference_id' => $preference->id], 200);
        } catch (\Exception $e) {
            Log::error('Error al crear la preferencia: ' . $e->getMessage());
            return response()->json(['message' => 'Excepción', 'error' => $e->getMessage()], 400);
        }
    }

    public function notify(Request $request)
    {
        try {
            Log::info('Notificación recibida: ', $request->all());

            if ($request->input('topic') == 'merchant_order') {
                $merchantOrderId = $request->input('id');
                Log::info('Merchant Order ID: ' . $merchantOrderId);

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MP_TOKEN'),
                ])->get('https://api.mercadopago.com/merchant_orders/' . $merchantOrderId);

                $merchantOrder = $response->json();
                Log::info('Merchant Order: ', $merchantOrder);

                $orderStatus = $merchantOrder['order_status'];
                $reserva_id = $merchantOrder['external_reference'];
                $reserva = Reserva::findOrFail($reserva_id);
                $nuevoEstado = null;

                switch ($orderStatus) {
                    case 'payment_in_process':
                        $nuevoEstado = 'Pendiente';
                        break;
                    case 'paid':
                        $nuevoEstado = 'Aceptado';
                        break;
                    case 'reverted':
                        $nuevoEstado = 'Rechazado';
                        break;
                    default:
                        Log::info('Estado de la orden no manejado: ' . $orderStatus);
                        return response()->json(['message' => 'OK'], 200);
                }

                $this->asignarEstado($reserva, $nuevoEstado);
                Log::info('Estado de la reserva actualizado: ' . $nuevoEstado);
            }
            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            Log::error('Error en la notificación: ' . $e->getMessage());
            return response()->json(['message' => 'Excepción', 'error' => $e->getMessage()], 400);
        }
    }

    private function asignarEstado(Reserva $reserva, $nuevoEstado)
    {
        $movimientos = $reserva->movimientosReserva()->get();
        $asignar = true;
        foreach ($movimientos as $movimiento) {
            if ($movimiento->estado == $nuevoEstado) {
                $asignar = false;
                break;
            }
        }
        if ($asignar) {
            $reserva->estado = $nuevoEstado;
            $reserva->save();
            Log::info('Estado asignado a la reserva: ' . $nuevoEstado);
        }
    }
}