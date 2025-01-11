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

            $items = [];
            $detalles = $reserva->detalle_reserva()->get();
            Log::info('Detalles de la reserva: ', $detalles->toArray());

            foreach ($detalles as $detalle) {
                $turno = Turno::find($detalle->id_turno);
                $cancha = Cancha::find($turno->id_cancha);

                $unit_price = max((float)$detalle->precio, 10.0); // Asegura un precio mínimo
                Log::info('Procesando ítem: Turno ID ' . $detalle->id_turno . ', Precio: ' . $unit_price);

                $item = new \MercadoPago\Item();
                $item->title = "Reserva de Cancha - " . $cancha->nombre;
                $item->description = "Turno: " . $turno->hora_turno . " | Fecha: " . $turno->fecha_turno;
                $item->quantity = 1;
                $item->unit_price = $unit_price;
                $item->currency_id = "ARS";

                $items[] = $item;
            }

            $preference = new Preference();
            $preference->items = $items;
            $preference->back_urls = [
                "success" => env('API_JS'),
                "pending" => env('API_JS'),
                "failure" => env('API_JS')
            ];
            $preference->auto_return = "approved";
            $preference->external_reference = $reserva_id;
            $preference->notification_url = env('NOTIFY_MP');


            Log::info('Datos enviados a MercadoPago: ', (array)$preference);

            Log::info('Guardando preferencia en MercadoPago...');
            $preference->save();

            // Validar respuesta de MercadoPago
            if (isset($preference->error)) {
                Log::error('Error devuelto por MercadoPago: ', (array)$preference->error);
                throw new \Exception('Error de MercadoPago: ' . json_encode($preference->error));
            }

            Log::info('Respuesta de MercadoPago tras guardar la preferencia: ', (array)$preference);

            // Consultar detalles adicionales de la preferencia
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MP_TOKEN'),
            ])->get('https://api.mercadopago.com/checkout/preferences/' . $preference->id);

            Log::info('Detalles de la preferencia consultada desde MercadoPago:', $response->json());

            if (!isset($preference->id)) {
                throw new \Exception('No se recibió un ID de preferencia de MercadoPago');
            }

            $reserva->preference_id = $preference->id;
            $reserva->save();

            Log::info('Preferencia guardada en la reserva: ' . $reserva->preference_id);

            return response()->json(['preference_id' => $preference->id], 200);
        } catch (\Exception $e) {
            Log::error('Error al crear la preferencia: ' . $e->getMessage());
            Log::error('Detalles del error: ', $e->getTrace());
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
        if ($reserva->estado !== $nuevoEstado) {
            $reserva->estado = $nuevoEstado;
            $reserva->updated_at = now();
            $reserva->save();

            Log::info('Estado actualizado para la reserva ID: ' . $reserva->id . ' - Nuevo estado: ' . $nuevoEstado);
        } else {
            Log::info('El estado de la reserva ID: ' . $reserva->id . ' ya es: ' . $nuevoEstado);
        }
        $this->actualizarDetallesReserva($reserva, $nuevoEstado);
    }

    private function actualizarDetallesReserva(Reserva $reserva, $nuevoEstado)
    {
        if ($nuevoEstado === 'Aceptado') {
            $detalles = DetalleReserva::where('id_reserva', $reserva->id)->get();

            foreach ($detalles as $detalle) {
                $detalle->updated_at = now();
                $detalle->save();
                Log::info('Detalle de reserva actualizado - ID Detalle: ' . $detalle->id . ' | Confirmado: true');
            }
        }
    }
}