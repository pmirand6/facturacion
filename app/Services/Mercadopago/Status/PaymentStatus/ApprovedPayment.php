<?php


namespace App\Services\Mercadopago\Status\PaymentStatus;

use App\Mail\RsnBilling;
use App\Manager\PaymentProcessorManager;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Mercadopago\Status\StatusFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApprovedPayment extends StatusFactory
{
    public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null)
    {
        $this->createLog($advancedPaymentResponse, $requestPayment);

        $this->savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling);

        $paymentStatus = collect($advancedPaymentResponse->payments);

        return response()->json([
            'error' => false,
            'status' => 'approved',
            'status_mp' => __("messages.{$advancedPaymentResponse->status}"),
            'response' => __("messages.{$paymentStatus[0]->status_detail}")
        ]);
    }

    public function createLog($advancedPaymentResponse, $requestPayment)
    {
        $paymentStatus = collect($advancedPaymentResponse->payments);
        Log::info(json_encode([
            'method' => __METHOD__,
            'error' => false,
            'status' => $advancedPaymentResponse->status,
            'params' => $requestPayment->all(),
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