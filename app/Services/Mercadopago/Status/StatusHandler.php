<?php

namespace App\Services\Mercadopago\Status;


use App\Services\Mercadopago\Values\PaymentStatusValues;
use Exception;
use Illuminate\Support\Facades\Log;

trait StatusHandler
{
    public function statusHandler($advancedPayment, $requestPayment, $shippingBilling = null)
    {
        try {
            $paymentStatusClass = PaymentStatusValues::strategy($advancedPayment->status);
            return (new $paymentStatusClass)->handler($advancedPayment, $requestPayment, $shippingBilling);
        } catch (Exception $e) {
            Log::error(json_encode([
                'error' => true,
                'response' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    'advacendPaymenResponse' => $advancedPayment,
                    'shippingBillig' => $shippingBilling,
                    'request' => $requestPayment
                ]
            ]));
            return response()->json([
                'error' => true,
                'status' => "error",
                'message' => $e->getMessage(),
                'method' => __METHOD__,
                'params' => [
                    'advacendPaymenResponse' => $advancedPayment,
                    'shippingBillig' => $shippingBilling,
                    'request' => $requestPayment
                ]
            ]);
        }

    }
}