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
                $unit_price = max((float)$detalle->precio, 10.0);

                $item = new Item();
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
            $preference->save();

            if (isset($preference->error)) {
                throw new \Exception('Error de MercadoPago: ' . json_encode($preference->error));
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

            if ($request->input('topic') === 'merchant_order') {
                $merchantOrderId = $request->input('id');
                Log::info('Merchant Order ID: ' . $merchantOrderId);

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MP_TOKEN'),
                ])->get("https://api.mercadopago.com/merchant_orders/$merchantOrderId");

                $merchantOrder = $response->json();
                Log::info('Merchant Order completa: ', $merchantOrder);

                $reserva_id = $merchantOrder['external_reference'];
                $reserva = Reserva::findOrFail($reserva_id);

                $pagado = false;
                foreach ($merchantOrder['payments'] as $payment) {
                    if ($payment['status'] === 'approved') {
                        $pagado = true;
                        break;
                    }
                }

                $estadoFinal = $pagado ? 'Aceptado' : 'Cancelado';
                $this->asignarEstado($reserva, $estadoFinal);
                Log::info("Estado de la reserva $reserva_id actualizado a: $estadoFinal");
            }

            if ($request->input('topic') === 'payment') {
                $paymentId = $request->input('id');
                Log::info('Payment ID: ' . $paymentId);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('MP_TOKEN'),
                ])->get("https://api.mercadopago.com/v1/payments/$paymentId");

                $paymentData = $response->json();
                Log::info('Datos del pago: ', $paymentData);

                $reserva_id = $paymentData['external_reference'] ?? null;
                $status = $paymentData['status'] ?? 'unknown';

                if ($reserva_id) {
                    $reserva = Reserva::findOrFail($reserva_id);

                    $estadoFinal = match ($status) {
                        'approved' => 'Aceptado',
                        'pending', 'in_process' => 'Pendiente',
                        'rejected', 'cancelled', 'expired' => 'Cancelado',
                        default => 'Cancelado',
                    };

                    $this->asignarEstado($reserva, $estadoFinal);
                    Log::info("Estado de la reserva $reserva_id actualizado por payment a: $estadoFinal");
                } else {
                    Log::warning("No se encontró external_reference en el pago ID $paymentId");
                }
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
        $detalles = DetalleReserva::where('id_reserva', $reserva->id)->get();

        foreach ($detalles as $detalle) {
            if ($nuevoEstado === 'Aceptado') {
                $detalle->updated_at = now();
                $detalle->save();
                Log::info('Detalle de reserva actualizado - ID Detalle: ' . $detalle->id . ' | Confirmado: true');
            } elseif ($nuevoEstado === 'Cancelado') {
                $detalle->cancelado = true;
                $detalle->updated_at = now();
                $detalle->save();
                Log::info('Detalle de reserva actualizado - ID Detalle: ' . $detalle->id . ' | Cancelado: true');
            }
        }
    }
}
