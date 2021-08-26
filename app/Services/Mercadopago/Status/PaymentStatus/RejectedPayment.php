<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;


use App\Manager\PaymentProcessorManager;
use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Support\Facades\Log;

class RejectedPayment extends StatusFactory
{


    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {

        $this->createLog($advancedPaymentResponse, $requestPayment);

        $this->savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling);

        $paymentStatus = collect($advancedPaymentResponse->payments)->toArray();

        return response()->json([
            'error' => true,
            'status' => 'error',
            'status_mp' => __("messages.{$paymentStatus[0]->status}"),
            'description' => __("messages.{$paymentStatus[0]->status_detail}")
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment)
    {
        $paymentStatus = collect($advancedPaymentResponse->payments)->toArray();

        Log::alert(json_encode([
            'method' => __METHOD__,
            'error' => true,
            'status' => __("messages.{$paymentStatus[0]->status}"),
            'description' => __("messages.{$paymentStatus[0]->status_detail}")
        ]));
    }

    public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        $request = collect([
            'advancedPayment' => $advancedPaymentResponse,
            'requestPayment' => $requestPayment,
            'shippingBilling' => $shippingBilling
        ]);

        app(PaymentProcessorManager::class)->savePaymentParameters($request);

        app(PaymentProcessorManager::class)->saveDisbursement($request);
    }
}