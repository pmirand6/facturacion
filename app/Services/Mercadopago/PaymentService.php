<?php


namespace App\Services\Mercadopago;

use Illuminate\Http\Request;

use MercadoPago\Payment;

class PaymentService
{
    public $payment = [];

    public function __construct($request)
    {
        $this->payment = $this->setPayment($request);
        return $this->payment;
    }

    /**
     * @param $requestgit fetch && git checkout bugfix/FER-1444-bug-invalid-disbursement-amount
     * @return Payment
     */
    private function setPayment($request)
    {
        $payment = new Payment();
        $payment->transaction_amount = (float)number_format((float)$request->total, 2, '.', '');
        $payment->token = $request->token_tarjeta;
        //$payment->description = $request->description;
        $payment->installments = (int)$request->installments;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->issuer_id = (int)$request->issuer_id;
        $payment->processing_mode = "aggregator";
        $payment->capture = true;
        $payment->external_reference = $request->external_reference;
        $payment->statement_descriptor = "Compra Feriame";


        return $payment;
    }

}