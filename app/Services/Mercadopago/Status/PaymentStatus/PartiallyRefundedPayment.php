<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;


use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Support\Facades\Log;

class PartiallyRefundedPayment extends StatusFactory
{
    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        $this->createLog($advancedPaymentResponse, $requestPayment);
        return response()->json([
            'error' => true,
            'status' => $advancedPaymentResponse->status,
            'description' => 'Fue devuelto parte del pago al usuario.'
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment)
    {
        Log::alert(json_encode([
            'method' => __METHOD__,
            'error' => false,
            'status' => $advancedPaymentResponse->status,
            'description' => 'Fue devuelto parte del pago al usuario.'
        ]));
    }

    public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        // TODO: Implement savePayment() method.
    }
}