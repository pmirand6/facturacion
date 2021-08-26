<?php


namespace App\Services\Mercadopago\Status;


abstract class StatusFactory
{
    abstract public function handler($advancedPaymentResponse, $requestPayment, $shippingBilling = null);

    abstract public function createLog($advancedPaymentResponse, $requestPayment);

    abstract public function savePayment($advancedPaymentResponse, $requestPayment, $shippingBilling = null);

}