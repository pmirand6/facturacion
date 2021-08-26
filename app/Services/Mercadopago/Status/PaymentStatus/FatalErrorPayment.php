<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;


use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FatalErrorPayment extends StatusFactory
{

    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null): JsonResponse
    {
        $this->createLog($advancedPaymentResponse, $requestPayment);

        return response()->json([
            'error' => true,
            'status' => 'error',
            'response' => [$advancedPaymentResponse->error]
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment): void
    {
        Log::error(json_encode([
            'method' => __METHOD__,
            'error' => true,
            'status' => 'error',
            'description' => 'Error fatal',
            'response' => [$advancedPaymentResponse->error],
        ]));
    }

    public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        // TODO: Implement savePayment() method.
    }
}