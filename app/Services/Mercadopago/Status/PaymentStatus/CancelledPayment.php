<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;


use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CancelledPayment extends StatusFactory
{

    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null): JsonResponse
    {
        $this->createLog($advancedPaymentResponse, $requestPayment);
        return response()->json([
            'error' => true,
            'status' => $advancedPaymentResponse->status,
            'description' => 'El pago fue cancelado por una de las partes o el pago expiró.'
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment)
    {
        Log::alert(json_encode([
            'method' => __METHOD__,
            'error' => true,
            'status' => $advancedPaymentResponse->status,
            'description' => 'El pago fue cancelado por una de las partes o el pago expiró.'
        ]));
    }

    public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        // TODO: Implement savePayment() method.
    }
}