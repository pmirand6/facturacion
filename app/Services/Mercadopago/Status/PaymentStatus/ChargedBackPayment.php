<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;


use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Support\Facades\Log;

class ChargedBackPayment extends StatusFactory
{

    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        $this->createLog($advancedPaymentResponse, $requestPayment);
        return response()->json([
            'error' => true,
            'status' => $advancedPaymentResponse->status,
            'description' => 'Se ha realizado un contracargo en la tarjeta de crédito del comprador.'
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment)
    {
        Log::alert(json_encode([
            'method' => __METHOD__,
            'error' => true,
            'status' => $advancedPaymentResponse->status,
            'description' => 'Se ha realizado un contracargo en la tarjeta de crédito del comprador.'
        ]));
    }

    public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        // TODO: Implement savePayment() method.
    }
}